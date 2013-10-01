@extends('_layouts.master')

@section('main')
	<div class="row">
		<div class="content">
			<h2 class="hd">Današnja jela</h2>

			@if ($dishes)
				<table class="table table-striped table-bordered">
					<thead>
						<tr>
							<th class="index"><i class="glyphicon glyphicon-cutlery"></i></th>
							<th><i class="glyphicon glyphicon-pencil"></i></th>
							<th><i class="glyphicon glyphicon-shopping-cart"></i></th>
							<th class="actions"><i class="glyphicon glyphicon-cog"></i></th>
						</tr>
					</thead>
					<tbody>
						@foreach ($dishes as $dish)
							{{ Form::model($dish, array('route' => array('dishes.update', $dish->id), 'method' => 'put')) }}
								<tr>
									<td class="code index">{{ $dish->code }}. </td>
									<td class="name">{{ Form::text('title', null, array('placeholder' => 'Ime jela', 'class' => 'form-control')) }}</td>
									<td class="price">
										<div class="input-group">
											{{ Form::text('price', null, array('placeholder' => 'Cijena', 'class' => 'form-control')) }}
											<span class="input-group-addon">kn</span>
										</div>
									</td>
									<td class="actions"><button type="submit" class="btn btn-mini btn-success"><i class="glyphicon glyphicon-save"></i></button></td>
								</tr>
							{{ Form::close() }}
						@endforeach
					</tbody>
				</table>
			@endif
		</div>
	</div>
@stop
