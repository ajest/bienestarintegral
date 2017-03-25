@extends('layouts.template')

@section('nav')
	@include('partials/menuhome')
@endsection

@section('content')
	<div class="row col-md-12">
		<h1>{{ $patient->name }}</h1>
		<small><a href="#" title="Ver paciente Pablo Fumarola en el calendario">Ver en calendario</a></small>
		<a class="btn btn-primary pull-right" href="{{ url('/patients/' . $patient->id . '/edit') }}">Editar</a>
		<a class="btn btn-success pull-right" href="{{ url('/patients') }}">Volver</a>
		<hr />
		<div class="col-md-4">
			<div class="col-md-12">
				<h3>Historial</h3>
				<ul>
					<li><strong>17/06/1989:</strong> para reflexología con <span class="label label-primary">David Lebenfisz</span>
					</li>
					<li><strong>17/06/1989:</strong> para reflexología con <span class="label label-primary">David Lebenfisz</span>
					</li>
					<li><strong>17/06/1989:</strong> para reflexología con <span class="label label-primary">David Lebenfisz</span>
					</li>
				</ul>
			</div>
		</div>
		<div class="col-md-8">
			<div class="col-md-6">
				<h3>Información personal</h3>
				<p><strong>Nombre</strong> {{ $patient->name }}</a></p>
				<p><strong>Email</strong> <a href="mailto:{{ $patient->email }}">{{ $patient->email }}</a></p>
				<p><strong>Teléfono</strong> {{ $patient->tel }}</p>
				<p><strong>Dirección</strong> {{ $patient->address }}</p>
			</div>
			<div class="col-md-6">
				<h3>Próximos turnos</h3>
				<p><strong>20/04/2018</strong> para reflexología con <span class="label label-primary">David Lebenfisz</span></p>
				<p><strong>20/04/2018</strong> para reflexología con <span class="label label-primary">David Lebenfisz</span></p>
				<p><strong>20/04/2018</strong> para reflexología con <span class="label label-primary">David Lebenfisz</span></p>
			</div>
		</div>
	</div>
@endsection