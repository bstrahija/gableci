@extends('_layouts.master')

@section('main')

	<div class="row">
		<div class="flyer span3">
			@if ($flyer)
				<h2>Danas</h2>
				<a href="{{ $flyer['href'] }}" class="lightbox"><img src="{{ $flyer['src'] }}" alt=""></a>
			@endif
		</div>

		<div class="content span9">
			<div class="my-reservation">
				<h2>Moja rezervacija za danas</h2>

				{{ Form::model($myReservation, array('class' => 'form-horizontal', 'route' => 'reservations.post')) }}
					<div class="control-group">
						<label for="dish" class="control-label">Jelo</label>
						<div class="controls">
							{{ Form::select('dish', array(
								'' => 'Biraj...',
								1 => Dish::getTitleByCode(1),
								2 => Dish::getTitleByCode(2),
								3 => Dish::getTitleByCode(3),
								4 => Dish::getTitleByCode(4),
								5 => Dish::getTitleByCode(5),
								6 => Dish::getTitleByCode(6),
								7 => Dish::getTitleByCode(7),
							)) }}
						</div>
					</div>
					<div class="control-group">
						<label for="notes" class="control-label">Napomena</label>
						<div class="controls">{{ Form::textarea('notes', null, array('rows' => 2)) }}</div>
					</div>
					<div class="form-actions">
						<button type="submit" class="btn btn-primary">Spremi</button>
					</div>
				{{ Form::close() }}
			</div>

			<hr>

			<div class="reservations">
				<h2>Ostale rezervacije</h2>

				<div id="reservation-reload">
					@include('reservations.overview')
				</div>
			</div>
		</div>
	</div>

	<br><br><br>
@stop
