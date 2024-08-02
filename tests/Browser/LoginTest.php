<?php

namespace Tests\Browser;

use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseTruncation;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Laravel\Dusk\Browser;
use Tests\DuskTestCase;

class LoginTest extends DuskTestCase
{
    use DatabaseTruncation;

    /**
     * Indicates which tables should be excluded from truncation.
     *
     * @var array
     */
    // protected $exceptTables = ['users'];

    public function setUp(): void
    {
        parent::setUp();
        $this->artisan('db:seed');
    }

    /**
     * A Dusk test example.
     */
    public function testExample(): void
    {
        $this->browse(function(Browser $browser) {
            $browser->visit('/')
                    ->assertSee('Laravel');
        });

        dump(User::all()->first()->toArray());

        $this->browse(function(Browser $browser) {
            $browser->visit('/login')
                    ->pause(2000)
                    ->assertSee('Correo electrónico')
                    ->assertSee('Contraseña');

            // Set the value...
            $browser->value('input[name="email"]', 'chrisd@mindflow.com.au');
            $browser->value('input[name="password"]', 'SrgFin89734$$$');
            $browser->pause(500);
            $browser->click('form button[type="submit"]');
            $browser->pause(2000)
                    ->assertPathIs('/calendar')
                    ->assertSee('Calendario')
                    ->assertSee('Chris');

            // ---
            $browser->pause(3000);
        });
    }
}
