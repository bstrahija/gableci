<!doctype html>
<html lang="en">
<head>
	<meta charset="utf-8">
	<title>Gableci</title>

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
