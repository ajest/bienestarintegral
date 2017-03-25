@extends('layouts.template')

@section('nav')
	@include('partials/menuhome')
@endsection

@section('content')
	<div class="row col-md-12">
		<form method="POST" action="{{ url('appointments/' . (!empty($edit) ? $appointment->id : 'create')) }}">
			@if(!empty($edit))
				{{ method_field('PUT') }}
				<input type="hidden" name="id" value="{{ $appointment->id }}">
			@endif
			{{ csrf_field() }}
			<div class="row col-md-12">
				<h1>{{ !empty($appointment->title) ? $appointment->title : 'Nuevo turno' }}</h1>				
			</div>
			<div class="row col-md-12">
				<a class="pull-right btn btn-success" href="{{ url('/appointments') }}">Volver</a>
				<input class="submit pull-right btn btn-primary" value="Guardar" type="submit">
			</div>
			<hr />
			@include('partials/form_errors')
			<div class="col-md-12">
				<h3>Información personal</h3>
				<div class="form-group col-md-4">
					<label>Titulo</label>
					<input type="text" name="title" value="{{ !empty($appointment->title) ? $appointment->title : (old('title')) }}" placeholder="Ej. Turno Urgente" class="form-control">
				</div>
				<div class="form-group col-md-4">
					<label>Fecha *</label>
					<input type="text" name="date" value="{{ !empty($appointment->date) ? $appointment->date : (old('date')) }}" placeholder="Ej. 15/07/2017" class="form-control" required>
				</div>
				<div class="form-group col-md-4">
					<label>Hora *</label>
					<input type="text" name="hour" value="{{ !empty($appointment->hour) ? $appointment->hour : (old('hour')) }}" placeholder="Ej. 19:00" class="form-control" required>
				</div>
				<div class="form-group col-md-4">
					<label>Profesional</label>
					<select name="professional_id" class="form-control">
						<option value="">-- Seleccione Profesional --</option>
						@foreach($professionals as $professional)
							<option value="{{ $professional->id }}"
							{{ (!empty($appointment->professional->id) ? $appointment->professional->id : (old('professional_id'))) == $professional->id ? 'selected' : '' }}>{{ $professional->name }}</option>
						@endforeach
					</select>
				</div>
				<div class="form-group col-md-4">
					<label>Paciente *</label>
					<select name="patient_id" class="form-control" required>
						@foreach($patients as $patient)
							<option value="{{ $patient->id }}"
							{{ (!empty($appointment->patient->id) ? $appointment->patient->id : (old('patient_id'))) == $patient->id ? 'selected' : '' }}>{{ $patient->name }}</option>
						@endforeach
					</select>
				</div>
				<div class="form-group col-md-4">
					<label>Especialidad</label>
					<select name="specialty_id" class="form-control">
						<option value="">-- Seleccione Especialidad --</option>
						@foreach($specialties as $specialty)
							<option value="{{ $specialty->id }}"
							{{ (!empty($appointment->specialty->id) ? $appointment->specialty->id : (old('specialty_id'))) == $specialty->id ? 'selected' : '' }}>{{ $specialty->specialty }}</option>
						@endforeach
					</select>
				</div>
				<div class="form-group col-md-4">
					<label>Tratamiento</label>
					<select name="treatment_id" class="form-control">
						<option value="">-- Seleccione Tratamiento --</option>
						@foreach($treatments as $treatment)
							<option value="{{ $treatment->id }}"
							{{ (!empty($appointment->treatment->id) ? $appointment->treatment->id : (old('treatment_id'))) == $treatment->id ? 'selected' : '' }}>{{ $treatment->treatment }}</option>
						@endforeach
					</select>
				</div>
				<div class="form-group col-md-4">
					<label>Promoción</label>
					<select name="series_id" class="form-control">
						<option value="">-- Seleccione Promoción --</option>
						@foreach($series as $ser)
							<option value="{{ $ser->id }}"
							{{ (!empty($appointment->series->id) ? $appointment->series->id : (old('treatment_id'))) == $ser->id ? 'selected' : '' }}>{{ $ser->series }}</option>
						@endforeach
					</select>
				</div>
			</div>
		</form>
	</div>
@endsection