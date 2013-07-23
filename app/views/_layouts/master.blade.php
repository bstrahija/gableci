<!doctype html>
<html lang="en">
<head>
	<meta charset="utf-8">
	<title>Gableci</title>
	<meta name="viewport" content="width=device-width,initial-scale=1">

	@include('_partials.assets')
</head>
<body>
<div class="container">
	@include('_partials.header')

	@yield('main')

	@include('_partials.footer')
</div>
</body>
</html>
