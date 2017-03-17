@extends('layouts.template')

@section('nav')
	@include('partials/menuhome')
@endsection

@section('content')
	<table class="table table-striped">
		<thead>
			<tr>
				<td>Email</td>
				<td>Teléfono</td>
				<td>Sexo</td>
				<td>Dirección</td>
				<td>Acciones</td>
			</tr>
		</thead>
		<tbody>
			@foreach ($patients as $patient)
				<tr>
					<td>{{ $patient->email }}</td>
					<td>{{ $patient->tel }}</td>
					<td>{{ $patient->gender }}</td>
					<td>{{ $patient->address }}</td>	
					<td>
						<a href="{{ url('/patients/' . $patient->id) }}" title="Ver paciente">Ver</a>
						<a href="{{ url('/patients/') }}" title="Ver paciente">Editar</a>
						<a href="{{ url('/patients/') }}" title="Ver paciente">Eliminar</a>
					</td>			
				</tr>
			@endforeach
		</tbody>
	</table>
@endsection