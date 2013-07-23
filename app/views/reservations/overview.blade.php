@if ($reservations)
	<table class="table table-striped table-bordered">
		<thead>
			<tr>
				<th><i class="icon icon-user"></i></th>
				<th><i class="icon icon-food"></i></th>
				<th><i class="icon icon-pencil"></i> Napomena</th>
				<th><i class="icon icon-cog"></i></th>
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
	</table>

	<h3>Ukupno: <em>{{ Reservation::getTotalPrice() }} kn</em></h3>
@else
	<div class="alert">Nema rezervacija</div>
@endif
