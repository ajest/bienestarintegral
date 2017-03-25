@extends('layouts.template')

@section('nav')
	@include('partials/menuhome')
@endsection

@section('content')
	<div class="row col-md-12">
		<h1>{{ $appointment->title ? $appointment->title : '-- Sin título --' }}</h1>
		<small><a href="#" title="Ver paciente Pablo Fumarola en el calendario">Ver en calendario</a></small>
		<a class="btn btn-primary pull-right" href="{{ url('/appointments/' . $appointment->id . '/edit') }}">Editar</a>
		<a class="btn btn-success pull-right" href="{{ url('/appointments') }}">Volver</a>
		<hr />
		<div class="col-md-12">
			<div class="col-md-6">
				<h3>Información del turno</h3>
				<p><strong>Título</strong> {{ $appointment->title ? $appointment->title : '-- Sin título --' }}</a></p>
				<p><strong>Fecha y hora</strong> {{ $appointment->date . ' ' . $appointment->hour }}</p>
				<p><strong>Paciente</strong> {{ $appointment->patient->name . ' (' . $appointment->patient->email . ') ' }}</p>
			</div>
			<div class="col-md-6">
				<h3>Tratamiento</h3>
				<p><strong>Profesional</strong> {!! $appointment->professional ? '<span class="label label-primary">' . $appointment->professional->name . '</span>' : '--' !!}</p>
				<p><strong>Área / Especialidad</strong> {!! $appointment->specialty ? '<span class="label label-warning">' . $appointment->specialty->specialty . '</span>' : '--' !!}</p>
				<p><strong>Tratamiento</strong> {!! $appointment->treatment ? '<span class="label label-success">' . $appointment->treatment->treatment . '</span>' : '--' !!}</p>
				<p><strong>Promoción</strong> {!! $appointment->series ? '<span class="label label-danger">' . $appointment->series->series . '</span>' : '--' !!}</p>
			</div>
		</div>
	</div>
@endsection