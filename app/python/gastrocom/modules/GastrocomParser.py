#!/usr/bin/env python
# -*- coding: utf-8 -*-

'''

Retrieving gastrocom menu (pdf)
from url and parse it...

This library requires pdfminer.
You can install it using pip:
>> sudo pip install pdfminer

'''

import sys, os, re, datetime, json, requests

from pdfminer.pdfinterp import PDFResourceManager, PDFPageInterpreter
from pdfminer.converter import TextConverter
from pdfminer.layout import LAParams
from pdfminer.pdfpage import PDFPage
from cStringIO import StringIO

class GastrocomParser():

	### private variables
	__url = None
	__byte = None
	__plain = None
	__object = None
	__error = None

	### regex config
	__re_date = r'(\d{2})[/.-](\d{2})[/.-](\d{4})[/.-]?'
	__re_menu = r'MENU[^\n]+'
	__re_price = r'([0-9\,\.]+)'

	### numeric format config (decimal/thousand separator)
	__num_decimal = ','
	__num_thousand = ''

	### constructor
	def __init__(self, url):
		self.__url = url
		self.__byte = self.__get_byte()
		self.__plain = self.__get_plain()
		self.__object = self.__get_object()

	### convert url to absolute file path (if necessary)
	def __url_fixed(self):
		result = self.__url
		if len(result.split('://')) == 1:
			if result[0] != '/':
				result = os.path.realpath(os.path.dirname(os.path.realpath(sys.path[0])) + '/' + result)

		return result

	### read bytes in file (url)
	def __get_byte(self):
		result = None
		url = self.__url_fixed()

		if self.__error is None:
			try:
				if len(url.split('://')) == 1:
					f = open(url, 'r')
					result = f.read()
					f.close()
				else:
					request = requests.get(url, stream=True)
					raw = request.raw
					result = raw.read()
					del request
			except Exception, e:
				result = None
				self.__error = str(e)

		return result

	### extract text from pdf
	def __get_plain(self):
		result = None

		if self.__error is None:
			try:
				strio = StringIO(self.__byte)
				rsrcmgr = PDFResourceManager()
				retstr = StringIO()
				device = TextConverter(rsrcmgr, retstr, codec='utf-8', laparams=LAParams())
				interpreter = PDFPageInterpreter(rsrcmgr, device)
				for page in PDFPage.get_pages(strio, set(), maxpages=0, password='', caching=True, check_extractable=True):
					interpreter.process_page(page)
				strio.close()
				device.close()
				result = retstr.getvalue()
				retstr.close()
			except Exception, e:
				result = None
				self.__error = str(e)

		return result

	### parse pdf text
	def __get_object(self):
		result = {}
		result['date'] = None
		result['menu'] = []

		if self.__error is None:
			try:
				match = re.search(self.__re_date, self.__plain)
				if match is not None:
					result['date'] = str(datetime.date(*(map(int, match.groups()[-1::-1]))))

				menues = re.split(self.__re_menu, self.__plain)
				if menues is None:
					menues = []
				if len(menues) != 1:
					del menues[0]
				for i,menu in enumerate(menues):
					result['menu'].append(menu)

				for i,menu in enumerate(result['menu']):
					txt = menu.strip()
					arr = txt.split('Cijena')
					desc = arr[0]
					price = None

					match = re.search(self.__re_price, '' if len(arr) < 2 else arr[1])
					if match is not None:
						price = match.groups(1)[0]
						try:
							if bool(self.__num_thousand):
								price = price.replace(self.__num_thousand, '{THOUSAND}')
							if bool(self.__num_decimal):
								price = price.replace(self.__num_decimal, '{DECIMAL}')
							price = price.replace('{THOUSAND}', '').replace('{DECIMAL}', '.')
							price = float(price)
						except Exception, e:
							price = None

					result['menu'][i] = {
						'id': i,
						'desc': re.sub('\n+', '\n', desc.strip()),
						'price': price
					}

			except Exception, e:
				result = None
				self.__error = str(e)

		return result

	### get url
	def url(self):
		return self.__url

	### get error
	def error(self):
		return self.__error

	### get file content
	def byte(self):
		return self.__byte

	### get plain text from pdf file
	def plain(self):
		return self.__plain

	### get parsed object from pdf text
	def object(self):
		result = {}
		if self.__error is not None:
			result['error'] = self.__error
		else:
			result = self.__object

		return result

	### get object as json
	def json(self, pretty=False):
		return json.dumps(self.object(), indent=4 if pretty else None)
