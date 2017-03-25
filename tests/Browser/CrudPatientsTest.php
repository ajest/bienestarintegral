<?php

namespace Tests\Browser;

use Tests\DuskTestCase;
use Illuminate\Foundation\Testing\DatabaseMigrations;

class CrudPatientsTest extends DuskTestCase
{
    /**
     * Crea, edita y elimina un Paciente del sistema
     *
     * @return void
     */
    public function testGeneric()
    {
        $this->browse(function ($browser) {
            $browser->visit('/')
                    ->assertSee('Sistema de administración')
                    ->clickLink('Directorio')
                    ->assertSee('Directorio pacientes')
                    ->clickLink('Nuevo paciente')
                    ->assertSee('Nuevo Paciente')
                    ->type('name', 'Pablo Fumarola')
                    ->type('email', rand(0,100) . 'fumarola@gmail.com')
                    ->type('tel', '1568908443')
                    ->type('address', 'Emilio Lamarca 1616 Dto D')
                    ->select('gender', 'Hombre')
                    ->click('.submit')
                    ->assertSee('exitosa')
                    ->clickLink('Ver')
                    ->assertSee('Pablo Fumarola')
                    ->clickLink('Editar')
                    ->assertSee('Pablo Fumarola')
                    ->type('name', 'Pablo Joel Fumarola')
                    ->click('.submit')
                    ->assertSee('exitosa')
                    ->click('button.btn-danger')
                    ->assertSee('exitosa');
        });
    }
}
