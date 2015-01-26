<div class="hook" id="hook"></div>

@if (Sentry::check())
	<header>
		<div class="wrap">
			@if (isset($flyer) and $flyer)
				<a href="{{ $flyer['href'] }}" target="_blank" class="lightbox dish-menu"><i class="glyphicon glyphicon-picture"></i> Meni</a>
			@else
				<a href="#" class="dish-menu dish-menu-missing" title="Greška!"><i class="glyphicon glyphicon-ban-circle"></i> Meni</a>
			@endif

			<h1><a href="{{ url('/') }}">Gableci</a></h1>

			<a href="#" onclick="window.location.reload(true); return false;" class="refresh"><i class="glyphicon glyphicon-refresh"></i></a>
		</div>
	</header>

	<footer>
		<nav>
			<ul class="clearfix">
				@if ( ! Request::segment(1) or Request::segment(1) == 'reservations')
					<li class="stats {{ (Request::segment(1) == 'stats') ? 'active' : null }}"><a href="{{ URL::to('stats') }}"><i class="glyphicon glyphicon-stats"></i> Statistika</a></li>
				@else
					<li class="reservations {{ ( ! Request::segment(1) or Request::segment(1) == 'reservations') ? 'active' : null }}"><a href="{{ URL::route('reservations') }}"><i class="glyphicon glyphicon-ok"></i> Rezervacije</a></li>
				@endif

				<li class="dishes {{ (Request::segment(1) == 'dishes') ? 'active' : null }}"><a href="{{ URL::to('dishes') }}"><i class="glyphicon glyphicon-cutlery"></i> Jela</a></li>
			</ul>
		</nav>
	</footer>
@endif
