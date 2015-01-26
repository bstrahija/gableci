#!/usr/bin/env python
# -*- coding: utf-8 -*-

### import modules
import sys, os

### cli
if __name__ == '__main__':

	# arguments
	_url = None
	_prettify = None

	# display help
	def __help():
		print 'Parse gastrocom menu.'
		print ''
		print 'Usage:'
		print '  python ' + os.path.realpath(__file__) + ' [--url={url}] [--prettify]'
		print ''
		print 'Options:'
		print '  --url       URL of PDF menu'
		print '              (default: ' + _url + ')'
		print '  --prettify  Prettifies json output'
		print '              (default: false)'
		print '  --help      This help text'
		exit(0)

	# set defaults
	def __defaults():
		global _url, _prettify
		_url = 'http://www.gastrocom-ugostiteljstvo.com/media/k2/attachments/DNEVNI_MENU_{day}.{month}._Garestin.pdf'
		_prettify = False

	# get arguments
	def __args():
		global _url, _prettify

		if len(sys.argv) <= 0:
			__help()

		for argv in sys.argv[1:]:
			if argv == '--help':
				__help()
			elif argv == '--help=':
				__error('Wrong use of --help argument.', 1001)
			elif argv == '--url' or argv == '--url=':
				__error('Wrong use of --url argument.', 1002)
			elif argv[:6] == '--url=':
				_url = argv[6:]
			elif argv[:11] == '--prettify=':
				__error('Wrong use of --prettify argument.', 1003)
			elif argv[:10] == '--prettify':
				_prettify = True
			else:
				pass

	# fix url (date segments)
	def __fix_url():
		from datetime import datetime
		year = ('0' + str(datetime.now().year))[-4:]
		month = ('0' + str(datetime.now().month))[-2:]
		day = ('0' + str(datetime.now().day))[-2:]

		global _url
		_url = _url.replace('{year}', year).replace('{month}', month).replace('{day}', day)

	# execute
	def __exec():
		sys.path.append(os.path.dirname(os.path.realpath(__file__)) + '/modules')
		import GastrocomParser

		parser = GastrocomParser.GastrocomParser(_url)
		error = parser.error()
		print parser.json(_prettify)
		del parser

		exit(0 if error is None else 1000)

	# go, go, go...
	__defaults()
	__args()
	__fix_url()
	__exec()

