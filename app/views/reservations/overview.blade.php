@if ($reservations)
	<div class="reservation-overview">
		<ul>
			@foreach ($reservations as $reservation)
				<li>
					<h4>
						{{ $reservation->user->full_name }}
					</h4>

					<h3>
						<i class="glyphicon glyphicon-cutlery"></i>
						{{ Dish::getTitleByCode($reservation->dish) }}
					</h3>

					@if ($reservation->notes)
						<p>
							<i class="glyphicon glyphicon-bookmark"></i>
							{{ $reservation->notes }}
						</p>
					@endif
				</li>
			@endforeach
		</ul>
	</div>

	<?php /* <table class="table table-striped table-bordered">
		<thead>
			<tr>
				<th><i class="glyphicon glyphicon-user"></i></th>
				<th><i class="glyphicon glyphicon-cutlery"></i></th>
				<th><i class="glyphicon glyphicon-pencil"></i> Napomena</th>
				<th><i class="glyphicon glyphicon-cog"></i></th>
			</tr>
		</thead>
		<tbody>
			@foreach ($reservations as $reservation)
				<tr>
					<td>{{ $reservation->user->first_name }}</td>
					<td><strong>{{ Dish::getTitleByCode($reservation->dish) }}</strong></td>
					<td>{{ $reservation->notes }}</td>
					<td>
						@if (Sentry::getUser()->id == $reservation->user->id)
							<a href="#" class="btn btn-danger btn-mini"><i class="icon icon-remove"></i></a>
						@endif
					</td>
				</tr>
			@endforeach
		</tbody>
	</table> */ ?>

	<blockquote>
		<p>Ukupno: <em>{{ Reservation::getTotalPrice() }} kn</em></p>
	</blockquote>
@else
	<div class="alert">Nema rezervacija</div>
@endif
