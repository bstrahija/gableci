#!/usr/bin/env python
# -*- coding: utf-8 -*-

'''

Retrieving gastrocom menu (pdf)
from url and parse it...

This library requires pdfminer.
You can install it using pip:
>> sudo pip install pdfminer

If pip (python package manager)
is missing just do:
>> sudo apt-get install python-pip

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
	__binary = None
	__plain = None
	__object = None
	__error = None

	### constructor
	def __init__(self, url):
		self.__url = self.__fix_url(url)
		self.__binary = self.__get_binary()
		self.__plain = self.__get_plain()
		self.__object = self.__get_object()

	### read binarys in file (url)
	def __get_binary(self):
		result = None

		if self.__error is None:
			try:
				if len(self.__url.split('://')) == 1:
					f = open(self.__url, 'r')
					result = f.read()
					f.close()
				else:
					request = requests.get(self.__url, stream=True)
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
				strio = StringIO(self.__binary)
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
		result['error'] = self.__error;
		result['date'] = self.__re_date()
		result['menu'] = self.__re_menu()
		result['special'] = self.__re_special()

		if result['menu'] is not None:
			result['menu'] = sorted(result['menu'], key=lambda k: (k['id']))

		return result

	### extract date from text
	def __re_date(self):
		if not self.__error is None:
			return None

		regex = r'(\d{2})[/.-](\d{2})[/.-](\d{4})[/.-]?'
		match = re.search(regex, self.__plain)
		result = None

		if match is not None:
			try:
				result = datetime.datetime(*(map(int, match.groups()[-1::-1]))).isoformat()
			except Exception, e:
				result = None

		return result

	### extract menu from text
	def __re_menu(self):
		if not self.__error is None:
			return None

		# plain text
		plain = self.__plain

		# remove logo
		plain = plain.replace('G a\n\nr\n\ne\n\nsti n\n\npansion-restoran\n\ndnevni\n\n', '')
		plain = plain.replace('G a\n\nr\n\ne\n\nsti n\n\npansion-restoran\n\n', '')

		# remove date
		regex = r'.*\n\d{2}[/.-]\d{2}[/.-]\d{4}[/.-]?'
		plain = '\n' + re.sub(regex, '', plain)

		# remove day of week
		regex = r'\n(Ponedjeljak|Utorak|Srijeda|Četvrtak|Petak|Subota|Nedjelja)\n'
		plain = '\n' + re.sub(regex, '', plain).strip()

		# find regex pattern: {desc}MENU {id}{desc}Cijena: {price}
		regex = r'([\s\S]*?)\nMENU (.*)([\s\S]*?)C.*en.*: (.*)'
		match = re.findall(regex, plain)
		result = []

		# found nothing?
		if match is None:
			match = []

		# loop all and append menu to result
		for menu in match:
			if menu is not None:
				result.append({ 'id': self.__fix_id(menu[1]), 'desc': self.__fix_desc(menu[0] + menu[2]), 'price': self.__fix_price(menu[3]) })

		# that's it, we're done...
		return result

	### extract specials from text
	def __re_special(self):
		if not self.__error is None:
			return None

		regex = r'\nPOSEBNO VAM PREPORUČAMO:([\s\S]*?)(\nMENI|$)'
		match = re.search(regex, self.__plain)
		result = []

		if match is not None:
			for line in match.group(1).split('\n'):
				value = self.__fix_desc(line)
				if value != '':
					result.append(value)

		return result

	### convert url to absolute file path (if necessary)
	def __fix_url(self, value):
		if len(value.split('://')) == 1:
			if value[0] != '/':
				value = os.path.realpath(os.getcwd() + '/' + value)

		return value

	### conver roman numeral to int
	def __fix_id(self, value):
		value = re.sub('\n+', '\n', value)
		value = value.replace(' ', '')
		value = value.strip()

		if value.upper() == 'I': value = 1
		elif value.upper() == 'II': value = 2
		elif value.upper() == 'III': value = 3
		elif value.upper() == 'IV': value = 4
		elif value.upper() == 'V': value = 5
		elif value.upper() == 'VI': value = 6
		elif value.upper() == 'VII': value = 7
		elif value.upper() == 'VIII': value = 8
		elif value.upper() == 'IX': value = 9
		elif value.upper() == 'X': value = 10
		else: value = None

		return value

	### remove repeating newlines, lowercase, capitalize, trim
	def __fix_desc(self, value):
		value = unicode(value, 'utf-8')
		value = re.sub(ur'(\n+)([A-Z\u010c\u0106\u017d\u0160\u0110])', lambda pattern: ', ' + pattern.group(2).lower(), value)
		value = re.sub(r'\s+,', ',', value)
		value = re.sub(r', i ', ' i ', value, 0, re.IGNORECASE)
		value = re.sub(r'\s+,', ',', value)
		value = re.sub(r'^\*', '', value)
		value = re.sub(ur'^\u00b7', '', value)
		value = re.sub(ur'\u2013', '-', value)
		value = re.sub(r'-', ' - ', value)
		value = re.sub(r'\s+', ' ', value)
		value = value.strip(', ')
		value = value.lower().capitalize()

		return value

	### convert str to float
	def __fix_price(self, value):
		separator_decimal = ','
		separator_thousand = ''

		value = re.sub('[^\d.,]+', '', value)
		if bool(separator_thousand):
			value = value.replace(separator_thousand, '{THOUSAND}')
		if bool(separator_decimal):
			value = value.replace(separator_decimal, '{DECIMAL}')
		value = value.replace('{THOUSAND}', '').replace('{DECIMAL}', '.')

		try:
			value = float(value)
		except Exception, e:
			value = None

		return value

	### get url
	def url(self):
		return self.__url

	### get error
	def error(self):
		return self.__error

	### get file content
	def binary(self):
		return self.__binary

	### get plain text from pdf file
	def plain(self):
		return self.__plain

	### get parsed object from pdf text
	def object(self):
		return self.__object

	### get object as json
	def json(self, pretty=False):
		return json.dumps(self.object(), indent=4 if pretty else None)
