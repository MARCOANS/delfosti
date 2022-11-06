<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class SearchProductTest extends TestCase
{
    use DatabaseMigrations;

    /**
     * Test required term search.
     *
     * @return void
     */
    public function test_search_requires_a_search_term()
    {
        $response = $this->get(route('product.search'));

        $response->assertStatus(422);
        $response->assertJsonFragment(["term" => [
            "The term field is required."
        ]]);
    }

    /**
     * Test required term search.
     *
     * @return void
     */
    public function test_can_list_searched_products()
    {
        $this->seed();

        $response = $this->get('/api/products/search?term=blue');


        $response->assertStatus(200);
        $response->assertJsonStructure(
            [
                'data' => [
                    '*' => [
                        'id',
                        'name',
                        'description',
                        'status',
                        'created_at',
                        'updated_at',
                        'categories' => [
                            '*' => [
                                'id',
                                'name'
                            ]
                        ]
                    ]
                ]
            ]
        );
        $response->assertSee('blue', $escaped = true);
        $this->assertTrue(count($response['data']) >= 1);
    }
}
