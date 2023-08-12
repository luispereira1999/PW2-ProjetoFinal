<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use Database\Seeders\DatabaseSeeder;
use App\Models\Post;
use App\Models\User;

class HomeTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Configura o ambiente de teste.
     *
     * @return void
     */
    public function setUp(): void
    {
        parent::setUp();
        $this->seed(DatabaseSeeder::class);
    }


    /**
     * Ir para a página inicial.
     *
     * @test
     */
    public function test_home_route()
    {
        $user = User::first();
        $this->actingAs($user);  // autentica utilizador

        $response = $this->get(route('home'));

        $response->assertStatus(200);
        $response->assertViewIs('home');
        $response->assertViewHas('featuredPost');
        $response->assertViewHas('searchText');
        $response->assertViewHas('posts');
        $response->assertViewHas('loggedUserId');
    }


    /**
     * Pesquisar posts pelo título na página inicial.
     *
     * @test
     */
    public function test_search_route()
    {
        $user = User::first();
        $this->actingAs($user);  // autentica utilizador

        $searchText = 'hello world';

        $response = $this->get(route('search', ['searchText' => $searchText]));

        $response->assertStatus(200);
        $response->assertViewIs('home');
        $response->assertViewHas('featuredPost');
        $response->assertViewHas('searchText');
        $response->assertViewHas('posts');
        $response->assertViewHas('loggedUserId');
    }
}
