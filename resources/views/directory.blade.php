@extends('layouts.template')

@section('nav')
	@include('partials/menuhome')
@endsection

@section('content')
	<div class="row col-md-12">
		@include('partials/result_operation')
		<h1>Directorio pacientes <small><a href="{{ url('patients/create') }}" class="font-size-14">Nuevo paciente [+]</a></small></h1>				
	</div>
	<div class="row col-md-12">
		<table class="table table-striped table-hover">
			<thead>
				<tr>
					<td>Nombre</td>
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
						<td>{{ $patient->name }}</td>
						<td>{{ $patient->email }}</td>
						<td>{{ $patient->tel }}</td>
						<td>{{ $patient->gender }}</td>
						<td>{{ $patient->address }}</td>	
						<td class="row">
							<a class="pull-right btn btn-success" href="{{ url('patients/' . $patient->id) }}" title="Ver paciente">Ver</a>
							<a class="pull-right btn btn-primary" href="{{ url('patients/' . $patient->id . '/edit') }}" title="Editar paciente">Editar</a>
							@include('partials/delete_element_table', array(
								'url' 	=> URL::route('patients.destroy', $patient->id),
								'text' 	=> 'Eliminar',
								'class' => 'pull-right btn btn-danger'))
						</td>
					</tr>
				@endforeach
			</tbody>
		</table>
		{{ $patients->links() }}
	</div>
@endsection

@section('assets_jquery')
<script>

</script>
@endsection