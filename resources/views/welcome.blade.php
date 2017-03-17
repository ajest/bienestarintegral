@extends('layouts.template')

@section('nav')
	@include('partials/menuhome')
@endsection

@section('marketing')
	@include('partials/marketingwelcome')
@endsection

@section('content')
	<!-- Example row of columns -->
  	<div class="row">
	    <div class="col-md-4">
		    <h2>Programación</h2>
		    <p>Desarrollado con tecnologías actuales y seguras del lado del servidor, y con el siempre ágil framework de frontend Angular 2</p>
		    <p><a class="btn btn-default" href="#" role="button">Ver tecnologías &raquo;</a></p>
	    </div>
	    <div class="col-md-4">
		    <h2>Orientación</h2>
		    <p>Queremos facilitarte las cosas. Si tenés un negocio mediano dónde pretendés administrar pacientes y turnos, este es el sistema que necesitás.</p>
		    <p><a class="btn btn-default" href="#" role="button">Ver negocios &raquo;</a></p>
	    </div>
	    <div class="col-md-4">
		    <h2>Escalabilidad</h2>
		    <p>Con nuestro sistema, vas a tener todo lo que necesitás. ¿Querés aún más? No hay problem! Se pueden agregar complementos hechos a medida del cliente.</p>
		    <p><a class="btn btn-default" href="#" role="button">Ver ideas &raquo;</a></p>
    	</div>
  </div>
@endsection