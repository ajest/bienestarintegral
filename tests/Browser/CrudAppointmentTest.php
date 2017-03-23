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
            $browser->visit('/')
                    ->assertSee('Sistema de administración')
                    ->clickLink('Turnos')
                    ->assertSee('Turnos')
                    ->clickLink('Nuevo turno')
                    ->assertSee('Nuevo turno')
                    ->type('title', 'Turno urgente')
                    ->select('professional_id', 'David Lebenfisz')
                    ->select('patient_id', 'Bárbara Pedrozo')
                    ->select('specialty_id', 'Masajes')
                    ->select('treatment_id', 'Masajes reductores')
                    ->select('series_id', 'Reductor Noviembre y Diciembre')
                    ->type('date', '20/05/2017')
                    ->type('hour', '19:00')
                    ->click('.submit')
                    ->assertSee('exitosa')
                    ->clickLink('Ver')
                    ->assertSee('Turno urgente')
                    ->clickLink('Editar')
                    ->assertSee('Turno urgente')
                    ->type('title', 'Turno no tan urgente')
                    ->click('.submit')
                    ->assertSee('exitosa')
                    ->click('button.btn-danger')
                    ->assertDontSee('Turno no tan urgente');
        });
    }
}
