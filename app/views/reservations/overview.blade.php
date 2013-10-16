@if ($reservations)
	<div class="complete-overview">
		<h2 class="hd">Pregled rezervacija</h2>

		<ul>
			@foreach ($overview as $entry)
				<li>
					<strong>{{ $entry['count'] }}</strong> x
					<em class="code"><i class="glyphicon glyphicon-cutlery"></i> {{ $entry['dish'] }}</em>
					<em class="dish">{{ $entry['title'] }}</em>
					<span class="price">{{ $entry['price'] }} kn</span>

					@if (isset($entry['notes']) and $entry['notes'])
						<p class="notes">
							@foreach ($entry['notes'] as $note)
								<i class="glyphicon glyphicon-bookmark"></i>
								<i class="count">{{ $note['count'] }}</i>
								x
								<i class="text">{{ $note['text'] }}</i>
								<br>
							@endforeach
						</p>
					@endif
				</li>
			@endforeach
		</ul>

		<blockquote>
			<p>Ukupno: <em>{{ $totalPrice }} kn</em></p>
		</blockquote>
	</div>

	<hr>

	<div class="reservation-overview">
		<h2 class="hd">Sve rezervacije</h2>

		<ul>
			@foreach ($reservations as $reservation)
				<li>
					<h4>
						{{ $reservation->user->full_name }}
					</h4>

					<h3>
						<i class="glyphicon glyphicon-cutlery"></i>
						@if ($reservation->dish)
							{{ Dish::getTitleByCode($reservation->dish) }}
						@else
							---
						@endif
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

	<blockquote>
		<p>Ukupno: <em>{{ $totalPrice }} kn</em></p>
	</blockquote>
@else
	<div class="alert">Nema rezervacija</div>
@endif
