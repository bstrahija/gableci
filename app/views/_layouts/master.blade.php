<!doctype html>
<html lang="en">
<head>
	<meta charset="utf-8">
	<title>Gableci</title>
	<meta name="viewport" content="width=device-width,initial-scale=1,maximum-scale=1,user-scalable=no">

	<meta name="apple-mobile-web-app-status-bar-style" content="black-translucent">
	<meta name="apple-mobile-web-app-capable" content="yes">

	<link rel="apple-touch-icon" href="{{ asset('assets/img/iTunesArtwork.png') }}">

	@include('_partials.assets')
</head>
<body class="{{ isset($login) ? 'login-screen' : '' }}">
@include('_partials.header')

<div class="container">
	@yield('main')

	@include('_partials.footer')
</div>
</body>
</html>
