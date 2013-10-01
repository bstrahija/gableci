@extends('_layouts.master')

@section('main')

	<div class="row">
		<?php /*<div class="flyer col-md-3">
			@if ($flyer)
				<h2>Danas</h2>
				<a href="{{ $flyer['href'] }}" class="lightbox"><img src="{{ $flyer['src'] }}" alt=""></a>
			@endif
		</div>*/ ?>

		<div class="content">
			<div class="my-reservation">
				<h2 class="hd">Moja rezervacija za danas</h2>

				{{ Form::model($myReservation, array('route' => 'reservations.post')) }}
					<div class="form-group">
						<label for="dish" class="control-label">Jelo</label>
						<div class="controls">
							{{ Form::select('dish', array(
								'' => 'Biraj...',
								1 => Dish::getTitleByCode(1, false),
								2 => Dish::getTitleByCode(2, false),
								3 => Dish::getTitleByCode(3, false),
								4 => Dish::getTitleByCode(4, false),
								5 => Dish::getTitleByCode(5, false),
								6 => Dish::getTitleByCode(6, false),
								7 => Dish::getTitleByCode(7, false),
							), null, array('class' => 'form-control')) }}
						</div>
					</div>
					<div class="form-group">
						<label for="notes" class="control-label">Napomena</label>
						<div class="controls">{{ Form::textarea('notes', null, array('rows' => 2, 'class' => 'form-control')) }}</div>
					</div>
					<div class="form-actions">
						<button type="submit" class="btn btn-info">Spremi</button>
					</div>
				{{ Form::close() }}
			</div>

			<div class="reservations">
				<div id="reservation-reload">
					@include('reservations.overview')
				</div>
			</div>
		</div>
	</div>

	<br><br><br>
@stop
