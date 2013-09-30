@extends('_layouts.master')

@section('main')

{{ Form::open(array('route' => 'login.post')) }}

	<div id="login" class="login">

		<h1>Gableci</h1>

		@if ($errors->has('login'))
			<div class="alert alert-error">{{ $errors->first('login', ':message') }}</div>
		@endif

		<div class="form-group">
			{{ Form::label('email', 'Email') }}
			<div class="controls">
				{{ Form::text('email', null, array('class' => 'form-control')) }}
			</div>
		</div>

		<div class="form-group">
			{{ Form::label('password', 'Password') }}
			<div class="controls">
				{{ Form::password('password', array('class' => 'form-control')) }}
			</div>
		</div>

		<div class="form-actions">
			{{ Form::submit('Login', array('class' => 'btn btn-primary btn-login')) }}
		</div>

	</div>

{{ Form::close() }}

@stop
