<?php

namespace Tests\Feature;

use Tests\TestCase;

class PagesTest extends TestCase
{
    /**
     * A home page test.
     * @return void
     */
    public function test_home_page_is_successful()
    {
        $response = $this->get(route('home'));

        $response->assertStatus(200);
    }
}
