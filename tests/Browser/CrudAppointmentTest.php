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
                    ->assertSee('Bienestar')
                    ->clickLink('Turnos')
                    ->assertSee('Turnos')
                    ->clickLink('Nuevo turno')
                    ->assertSee('Nuevo turno')
                    /*
                    
                    

                    ->type('title', 'Turno urgente')
                    ->type('date', '20/05/2017')
                    ->type('hour', '19:00')
                    ->select('professional_id', 'Jazmyne Marks')
                    ->select('patient_id', 'Meda Howell')
                    ->select('specialty_id', 'Estética')
                    ->select('treatment_id', 'Ultasonido')
                    ->select('series_id', '2 x 1')
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
                    ->assertSee('exitosa')*/ ;
        });
    }
}
