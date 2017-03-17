@extends('layouts.template')

@section('nav')
	@include('partials/menuhome')
@endsection

@section('content')
	<div class="row col-md-12">
		<h1>Pablo Fumarola</h1>
		<small><a href="#" title="Ver paciente Pablo Fumarola en el calendario">Ver en calendario</a></small>
		<a class="btn btn-success pull-right" style="margin-left:10px;" href="{{ url('/patients') }}">Volver</a>
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
				<div class="form-group">
					<label>Email</label>
					<input type="email" name="email" value="{{ !empty($patient->email) ? $patient->email : '' }}" placeholder="Ej. David Lebenfisz" class="required form-control">
				</div>
				<div class="form-group">
					<label>Teléfono</label>
					<input type="text" name="tel" value="{{ !empty($patient->tel) ? $patient->tel : '' }}" placeholder="Ej. 15-6890-8443" class="required form-control">
				</div>
				<div class="form-group">
					<label>Dirección</label>
					<input type="text" name="address" value="{{ !empty($patient->address) ? $patient->address : '' }}" placeholder="Ej. Posta de Pardo 1050" class="required form-control">
				</div>				
			</div>
			<div class="col-md-6">
				<h3>Próximos turnos</h3>
				<p><strong>20/04/2018</strong> para reflexología con <span class="label label-primary">David Lebenfisz</span></p>
				<p><strong>20/04/2018</strong> para reflexología con <span class="label label-primary">David Lebenfisz</span></p>
				<p><strong>20/04/2018</strong> para reflexología con <span class="label label-primary">David Lebenfisz</span></p>
			</div>
		</div>
	</div>
	<div style="clear:both !important"></div>
@endsection