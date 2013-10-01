@extends('_layouts.master')

@section('main')
	<div class="row">
		<div class="content">

			<div class="spendings">
				<h2 class="hd">Potrošnja</h2>

				<div class="form-group spent-range-picker">
					{{ Form::select('range', array('' => 'Ukupno', date('Ym') => 'Ovaj mjesec', date('Ym', strtotime("-1 month")) => 'Prošli mjesec'), Input::get('range'), array('id' => 'spent-range', 'class' => 'form-control')) }}
				</div>

				@if ($spent)
					<ol>
						@foreach ($spent as $entry)
							<li>
								{{ $entry->user->full_name }}
								<em class="money">{{ (float) $entry->spent }} kn</em>
							</li>
						@endforeach
					</ol>
				@endif
			</div>

		</div>
	</div>
@stop
