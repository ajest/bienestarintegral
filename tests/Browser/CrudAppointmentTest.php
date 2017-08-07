<?php

namespace Tests\Browser;

use Tests\DuskTestCase;
use Illuminate\Foundation\Testing\DatabaseMigrations;

class CrudAppointmentTest extends DuskTestCase
{
    /**
     * A Dusk test example.
     *
     * @return void
     */
    public function testGeneric()
    {
        $this->browse(function ($browser) {

            $unique_moment_specialty = date('ymdhis');
            $unique_moment_treatment = date('ymdhis');

            $browser->visit('/')
                    ->assertSee('Iniciar sesión')
                    ->type('#email', 'pablo.fumarola@gmail.com')
                    ->type('#password', '123456')
                    ->click('button.btn')

            // Appointments        
                    ->visit('/#/appointments')
                    ->waitForText('Turnos')
                    ->clickLink('Nuevo turno')
                    ->type('#title', 'Turno urgente')
                    ->click('#date')
                    ->click('.cell.day.selected.highlighted.today')
                    ->type('#hour', '19:00')
                    ->select('#professional_id', 2)
                    ->select('#patient_id', 2)
                    ->select('#specialty_id', 2)
                    ->select('#treatment_id', 2)
                    ->select('#series_id', 2)
                    ->click('button.btn')
                    ->waitForText('correctamente')
                    ->type('input', 'Turno urgente')
                    ->pause(1000)
                    ->mouseover('.list-item')
                    ->click('.three-buttons a')
                    ->waitForText('Turno urgente')
                    ->clickLink('Editar')
                    ->waitForText('Turno urgente')
                    ->type('#title', 'Turno no tan urgente')
                    ->click('button.btn')
                    ->waitForText('Turno no tan urgente')
                    ->pause(500)
                    ->clickLink('Listado')
                    ->type('input', 'Turno no tan urgente')
                    ->pause(1000)
                    ->mouseover('.list-item')
                    ->click('.btn-danger')
                    ->pause(500)
                    ->click('.modal-footer .btn-danger')
                    
            // Patients
                    ->visit('/#/patients')
                    ->waitForText('Directorio pacientes')
                    ->clickLink('Nuevo paciente')
                    ->type('#name', 'Paciente nuevo')
                    ->type('#email', 'pacientenuevo' . date('ymdhis') . '@gmail.com')
                    ->type('#cellphone', '541168908443')
                    ->type('#tel', '541168908443')
                    ->type('#dni', '34539064')
                    ->select('#civil_status_id', 2)
                    ->select('#gender_id', 2)
                    ->type('#address', 'Posta de Pardo 123')
                    ->type('#area', 'CABA')
                    ->type('#facebook', 'pacientenuevo123')
                    ->click('button.btn')
                    ->waitForText('correctamente')
                    ->type('input', 'Paciente nuevo')
                    ->pause(1000)
                    ->mouseover('.list-item')
                    ->click('.three-buttons a')
                    ->waitForText('Paciente nuevo')
                    ->clickLink('Editar')
                    ->waitForText('Paciente nuevo')
                    ->type('#name', 'Paciente no tan nuevo')
                    ->click('button.btn')
                    ->waitForText('Paciente no tan nuevo')
                    ->pause(500)
                    ->clickLink('Listado')
                    ->type('input', 'Paciente no tan nuevo')
                    ->pause(1000)
                    ->mouseover('.list-item')
                    ->click('.btn-danger')
                    ->pause(500)
                    ->click('.modal-footer .btn-danger')
                    ->waitForText('correctamente')

            // Professionals
                    ->visit('/#/professionals')
                    ->waitForText('Profesionales')
                    ->clickLink('Nuevo profesional')
                    ->type('#name', 'Profesional nuevo')
                    ->type('#email', 'profesionalnuevo' . date('ymdhis') . '@gmail.com')
                    ->type('#tel', '541168908443')
                    ->select('#gender_id', 2)
                    ->type('#address', 'Posta de Pardo 123')
                    ->click('button.btn')
                    ->waitForText('correctamente')
                    ->type('input', 'Profesional nuevo')
                    ->pause(1000)
                    ->mouseover('.list-item')
                    ->click('.three-buttons a')
                    ->waitForText('Profesional nuevo')
                    ->clickLink('Editar')
                    ->waitForText('Profesional nuevo')
                    ->type('#name', 'Profesional no tan nuevo')
                    ->click('button.btn')
                    ->waitForText('Profesional no tan nuevo')
                    ->pause(500)
                    ->clickLink('Listado')
                    ->type('input', 'Profesional no tan nuevo')
                    ->pause(1000)
                    ->mouseover('.list-item')
                    ->click('.btn-danger')
                    ->pause(500)
                    ->click('.modal-footer .btn-danger')
                    ->waitForText('correctamente')

            // Professionals
                    ->visit('/#/series')
                    ->waitForText('Promociones')
                    ->clickLink('Nueva promoción')
                    ->type('#series', 'Promoción nueva')
                    ->type('#cant', 2)
                    ->click('button.btn')
                    ->waitForText('correctamente')
                    ->type('input', 'Promoción nueva')
                    ->pause(1000)
                    ->mouseover('.list-item')
                    ->click('.three-buttons a')
                    ->waitForText('Promoción nueva')
                    ->clickLink('Editar')
                    ->waitForText('Promoción nueva')
                    ->type('#series', 'Promoción no tan nueva')
                    ->click('button.btn')
                    ->waitForText('Promoción no tan nueva')
                    ->pause(500)
                    ->clickLink('Listado')
                    ->type('input', 'Promoción no tan nueva')
                    ->pause(1000)
                    ->mouseover('.list-item')
                    ->click('.btn-danger')
                    ->pause(500)
                    ->click('.modal-footer .btn-danger')
                    ->waitForText('correctamente')

            // Specialties
                    ->visit('/#/specialties')
                    ->waitForText('Especialidades')
                    ->clickLink('Nueva especialidad')
                    ->type('#specialty', 'Especialidad nueva (#' . $unique_moment_specialty . ')')
                    ->type('#description', 'Esta es una nueva especialidad')
                    ->click('button.btn')
                    ->waitForText('correctamente')
                    ->type('input', 'Especialidad nueva (#' . $unique_moment_specialty . ')')
                    ->pause(1000)
                    ->mouseover('.list-item')
                    ->click('.three-buttons a')
                    ->waitForText('Especialidad nueva (#' . $unique_moment_specialty . ')')
                    ->clickLink('Editar')
                    ->waitForText('Especialidad nueva (#' . $unique_moment_specialty . ')')
                    ->type('#specialty', 'Especialidad no tan nueva (#' . $unique_moment_specialty . ')')
                    ->click('button.btn')
                    ->waitForText('Especialidad no tan nueva (#' . $unique_moment_specialty . ')')
                    ->pause(500)
                    ->clickLink('Listado')
                    ->type('input', 'Especialidad no tan nueva (#' . $unique_moment_specialty . ')')
                    ->pause(1000)
                    ->mouseover('.list-item')
                    ->click('.btn-danger')
                    ->pause(500)
                    ->click('.modal-footer .btn-danger')
                    ->waitForText('correctamente')
            
            // Treatments
                    ->visit('/#/treatments')
                    ->waitForText('Tratamientos')
                    ->clickLink('Nuevo tratamiento')
                    ->type('#treatment', 'Tratamiento nuevo (#' . $unique_moment_treatment . ')')
                    ->select('#specialty_id', 2)
                    ->type('#description', 'Esta es una descripción de una tratamiento nuevo')
                    ->click('button.btn')
                    ->waitForText('correctamente')
                    ->type('input', 'Tratamiento nuevo (#' . $unique_moment_treatment . ')')
                    ->pause(1000)
                    ->mouseover('.list-item')
                    ->click('.three-buttons a')
                    ->waitForText('Tratamiento nuevo (#' . $unique_moment_treatment . ')')
                    ->clickLink('Editar')
                    ->waitForText('Tratamiento nuevo (#' . $unique_moment_treatment . ')')
                    ->type('#treatment', 'Tratamiento no tan (#' . $unique_moment_treatment . ')')
                    ->click('button.btn')
                    ->waitForText('Tratamiento no tan (#' . $unique_moment_treatment . ')')
                    ->pause(500)
                    ->clickLink('Listado')
                    ->type('input', 'Tratamiento no tan (#' . $unique_moment_treatment . ')')
                    ->pause(1000)
                    ->mouseover('.list-item')
                    ->click('.btn-danger')
                    ->pause(500)
                    ->click('.modal-footer .btn-danger')
                    ->waitForText('correctamente')

               // Questions
                    ->visit('/#/questions')
                    ->waitForText('Preguntas')
                    ->clickLink('Nueva pregunta')
                    ->type('#question', 'Pregunta nueva')
                    ->select('#specialty_id', 2)
                    ->click('button.btn')
                    ->waitForText('correctamente')
                    ->type('input', 'Pregunta nueva')
                    ->pause(1000)
                    ->mouseover('.list-item')
                    ->click('.three-buttons a')
                    ->waitForText('Pregunta nueva')
                    ->type('#question', 'Pregunta no tan nueva')
                    ->click('button.btn')
                    ->pause(500)
                    ->type('input', 'Pregunta no tan nueva')
                    ->pause(1000)
                    ->mouseover('.list-item')
                    ->click('.btn-danger')
                    ->pause(500)
                    ->click('.modal-footer .btn-danger')
                    ->waitForText('correctamente')
            ;
        });
    }
}