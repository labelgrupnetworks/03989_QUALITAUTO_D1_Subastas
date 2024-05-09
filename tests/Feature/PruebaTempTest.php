<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Providers\ToolsServiceProvider as Tools;

class PruebaTempTest extends TestCase
{
    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_temporal_to_make_other_tests()
    {
        $response = $this->get('/es/pagina/politica-de-privacidado');

        $response->assertStatus(404);
    }
}
