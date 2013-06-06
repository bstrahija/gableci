<header>
	<ul class="nav nav-pills pull-right">
		<li class="{{ ( ! Request::segment(1) or Request::segment(1) == 'reservations') ? 'active' : null }}"><a href="{{ URL::route('reservations') }}">Rezervacije</a></li>
		<li class="{{ (Request::segment(1) == 'dishes') ? 'active' : null }}"><a href="{{ URL::to('dishes') }}">Jela</a></li>
	</ul>

	<h1>Gableci</h1>
</header>

<hr>
