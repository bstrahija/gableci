@extends('_layouts.master')

@section('main')
	<div class="row">
		<div class="span12">
			<h2>Današnja jela</h2>

			@if ($dishes)
				<table class="table table-striped table-bordered">
					<thead>
						<tr>
							<th><i class="icon icon-food"></i></th>
							<th><i class="icon icon-pencil"></i></th>
							<th><i class="icon icon-money"></i></th>
							<th><i class="icon icon-cog"></i></th>
						</tr>
					</thead>
					<tbody>
						@foreach ($dishes as $dish)
							{{ Form::model($dish, array('route' => array('dishes.update', $dish->id), 'method' => 'put')) }}
								<tr>
									<td class="code">{{ $dish->code }}</td>
									<td class="name">{{ Form::text('title', null, array('placeholder' => 'Ime jela')) }}</td>
									<td class="price">
										<div class="input-append">
											{{ Form::text('price', null, array('placeholder' => 'Cijena')) }}
											<span class="add-on">kn</span>
										</div>
									</td>
									<td><button type="submit" class="btn btn-mini btn-success">Save</button></td>
								</tr>
							{{ Form::close() }}
						@endforeach
					</tbody>
				</table>
			@endif
		</div>
	</div>
@stop
