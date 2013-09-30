@extends('_layouts.master')

@section('main')
	<div class="row">
		<div class="content">

			<div class="spendings">
				<h2 class="hd">Potrošnja</h2>

				@if ($spent)
					<ol>
						@foreach ($spent as $entry)
							<li>
								{{ $entry->user->full_name }}
								<em class="money">{{ $entry->spent }} kn</em>
							</li>
						@endforeach
					</ol>
				@endif
			</div>

		</div>
	</div>
@stop
