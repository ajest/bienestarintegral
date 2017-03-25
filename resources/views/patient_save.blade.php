@extends('layouts.template')

@section('nav')
	@include('partials/menuhome')
@endsection

@section('content')
	<div class="row col-md-12">
		<form method="POST" action="{{ url('patients/' . (!empty($edit) ? $patient->id : 'create')) }}">
			@if(!empty($edit))
				{{ method_field('PUT') }}
				<input type="hidden" name="id" value="{{ $patient->id }}">
			@endif
			{{ csrf_field() }}
			<div class="row col-md-12">
				<h1>{{ !empty($patient->name) ? $patient->name : 'Nuevo Paciente' }}</h1>				
			</div>
			<div class="row col-md-12">
				<a class="pull-right btn btn-success" href="{{ url('/patients') }}">Volver</a>
				<input class="submit pull-right btn btn-primary" value="Guardar" type="submit">
			</div>
			<hr />
			@include('partials/form_errors')
			<div class="col-md-12">
				<h3>Información personal</h3>
				<div class="form-group col-md-4">
					<label>Name</label>
					<input type="text" name="name" value="{{ !empty($patient->name) ? $patient->name : (old('name')) }}" placeholder="Ej. David Lebenfisz" class="form-control" required>
				</div>
				<div class="form-group col-md-4">
					<label>Email</label>
					<input type="email" name="email" value="{{ !empty($patient->email) ? $patient->email : (old('email')) }}" placeholder="Ej. david@gmail.com" class="form-control" required>
				</div>
				<div class="form-group col-md-4">
					<label>Teléfono</label>
					<input type="text" name="tel" value="{{ !empty($patient->tel) ? $patient->tel : (old('tel')) }}" placeholder="Ej. 15-6890-8443" class="form-control" required>
				</div>
				<div class="form-group col-md-4">
					<label>Dirección</label>
					<input type="text" name="address" value="{{ !empty($patient->address) ? $patient->address : (old('address')) }}" placeholder="Ej. Posta de Pardo 1050" class="form-control" required>
				</div>
				<div class="form-group col-md-4">
					<label>Sexo</label>
					<select name="gender" class="form-control" required>
						<option value="H" 
						@if (!empty($patient->gender) && $patient->gender == 'H')
							selected
						@endif
							>Hombre</option>
						<option value="M" 
						@if (!empty($patient->gender) && $patient->gender == 'MD')
							selected
						@endif
							>Mujer</option>
					</select>
				</div>
			</div>
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
				<div class="col-md-12">
					<h3>Próximos turnos</h3>
					<p><strong>20/04/2018</strong> para reflexología con <span class="label label-primary">David Lebenfisz</span></p>
					<p><strong>20/04/2018</strong> para reflexología con <span class="label label-primary">David Lebenfisz</span></p>
					<p><strong>20/04/2018</strong> para reflexología con <span class="label label-primary">David Lebenfisz</span></p>
				</div>
			</div>
		</form>
	</div>
@endsection