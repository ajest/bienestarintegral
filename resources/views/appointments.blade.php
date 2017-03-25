@extends('layouts.template')

@section('nav')
	@include('partials/menuhome')
@endsection

@section('content')
	<div class="row col-md-12">
		@include('partials/result_operation')
		<h1>Turnos <small><a href="{{ url('appointments/create') }}" class="font-size-14">Nuevo turno [+]</a></small></h1>
	</div>
	<div class="row col-md-12">
		<table class="table table-striped table-hover">
			<thead>
				<tr>
					<td>Título</td>
					<td>Paciente</td>
					<td>Profesional</td>
					<td>Área / Especialidad</td>
					<td>Tratamiento</td>
					<td>Promoción</td>
					<td>Fecha</td>
					<td>Acciones</td>
				</tr>
			</thead>
			<tbody>
				@forelse ($appointments as $appointment)
					<tr>
						<td>{{ $appointment->title ? $appointment->title : '-- Sin título --' }}</td>
						<td>{{ $appointment->patient->name }}</td>
						<td>{{ $appointment->professional ? $appointment->professional->name : '-- No asignado --' }}</td>
						<td>{{ $appointment->specialty ? $appointment->specialty->specialty : '-- No asignada --' }}</td>
						<td>{{ $appointment->treatment ? $appointment->treatment->treatment : '-- No asignado --' }}</td>
						<td>{{ $appointment->series ? $appointment->series->series : '--' }}</td>
						<td>{{ $appointment->date . ' ' . $appointment->hour }}</td>
						<td class="row">
							<a class="pull-right btn btn-success" href="{{ url('appointments/' . $appointment->id) }}" title="Ver paciente">Ver</a>
							<a class="pull-right btn btn-primary" href="{{ url('appointments/' . $appointment->id . '/edit') }}" title="Editar paciente">Editar</a>
							@include('partials/delete_element_table', array(
								'url' 	=> URL::route('appointments.destroy', $appointment->id),
								'text' 	=> 'Eliminar',
								'class' => 'pull-right btn btn-danger'))
						</td>
					</tr>
				@empty
					<tr>
						<td colspan="3" class="middle-align">No hay registros</td>
					</tr>
				@endforelse
			</tbody>
		</table>
		{{ $appointments->links() }}
	</div>
@endsection

@section('assets_jquery')
<script>

</script>
@endsection