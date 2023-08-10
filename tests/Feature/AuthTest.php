<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use Database\Seeders\DatabaseSeeder;
use App\Models\User;

class AuthTest extends TestCase
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
     * Ir para a página de autenticação.
     *
     * @test
     */
    public function test_auth_route()
    {
        $response = $this->get(route('auth'));

        $response->assertStatus(200);
    }


    /**
     * Iniciar sessão de um utilizador.
     *
     * @test
     */
    public function test_auth_login_route()
    {
        $name = User::first()->name;

        $credentials = [
            'name_or_email' => $name,
            'password' => '123456'
        ];

        $response = $this->post(route('auth.login', $credentials));

        $response->assertStatus(200);
        // não usar "assertRedirect" porque o redirecionamento é feito do lado do cliente
    }


    /**
     * registar um utilizador.
     *
     * @test
     */
    public function test_auth_signup_route()
    {
        $data = [
            'name' => 'Luisa',
            'email' => 'luisa@test.com',
            'password' => '123456',
            'password_confirmation' => '123456',
        ];

        $response = $this->post(route('auth.signup', $data));

        $response->assertStatus(201);
        // não usar "assertRedirect" porque o redirecionamento é feito do lado do cliente
    }


    /**
     * Terminar sessão de um utilizador.
     *
     * @test
     */
    public function test_auth_logout_route()
    {
        $user = User::first();
        $this->actingAs($user);  // autentica utilizador

        $response = $this->get(route('auth.logout'));

        $response->assertRedirect(route('home'));
    }
}
