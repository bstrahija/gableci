<header>
	<div class="wrap">
		@if (Sentry::check() and isset($flyer) and $flyer)
			<a href="{{ $flyer['href'] }}" class="lightbox dish-menu"><i class="glyphicon glyphicon-picture"></i> Meni</a>
		@else
			<a href="#" class="dish-menu dish-menu-missing" title="Greška!"><i class="glyphicon glyphicon-ban-circle"></i> Meni</a>
		@endif

		<h1><a href="{{ url('/') }}">Gableci</a></h1>

		@if (Sentry::check())
			<a href="#" onclick="window.location.reload(true); return false;" class="refresh"><i class="glyphicon glyphicon-refresh"></i></a>
		@endif
	</div>
</header>

@if (Sentry::check())
	<footer>
		<nav>
			<ul class="clearfix">
				<li class="reservations {{ ( ! Request::segment(1) or Request::segment(1) == 'reservations') ? 'active' : null }}"><a href="{{ URL::route('reservations') }}"><i class="glyphicon glyphicon-ok"></i> Rezervacije</a></li>
				<li class="dishes {{ (Request::segment(1) == 'dishes') ? 'active' : null }}"><a href="{{ URL::to('dishes') }}"><i class="glyphicon glyphicon-cutlery"></i> Jela</a></li>
			</ul>
		</nav>
	</footer>
@endif
