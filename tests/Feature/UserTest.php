<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use Database\Seeders\DatabaseSeeder;
use App\Models\User;

class UserTest extends TestCase
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
     * Ir para a página do perfil de utilizador.
     *
     * @test
     */
    public function test_profile_route()
    {
        $user = User::first();

        $response = $this->get(route('profile', ['userId' => $user->id]));

        $response->assertStatus(200);
        $response->assertViewHas('user');
        $response->assertViewHas('posts');
        $response->assertViewHas('loggedUserId');
    }


    /**
     * Ir para a página de definições da conta.
     *
     * @test
     */
    public function test_account_route()
    {
        $user = User::first();
        $this->actingAs($user);  // autentica utilizador

        $response = $this->get(route('account', ['userId' => $user->id]));

        $response->assertStatus(200);
        $response->assertViewHas('user');
    }


    /**
     * Atualizar dados do utilizador.
     *
     * @test
     */
    public function test_account_edit_data_route()
    {
        $user = User::first();
        $this->actingAs($user);  // autentica utilizador

        $data = [
            'name' => 'Luisa',
            'email' => 'luisa@test.com',
            'first_name' => 'luisa@test.com',
            'last_name' => 'luisa@test.com',
            'city' => 'França',
            'country' => 'Paris'
        ];

        $response = $this->patch(route('account.edit-data', ['userId' => $user->id]), $data);

        $response->assertStatus(200);
    }


    /**
     * Atualizar palavra-passe do utilizador.
     *
     * @test
     */
    public function test_account_edit_password_route()
    {
        $user = User::first();
        $this->actingAs($user);  // autentica utilizador

        $data = [
            'current_password' => '123456',
            'new_password' => 'abcdef',
            'new_password_confirm' => 'abcdef'
        ];

        $response = $this->patch(route('account.edit-password', ['userId' => $user->id]), $data);

        $response->assertStatus(200);
    }


    /**
     * Remover um utilizador.
     *
     * @test
     */
    public function test_account_delete_route()
    {
        $user = User::first();
        $this->actingAs($user);  // autentica utilizador

        $response = $this->delete(route('account.delete', ['userId' => $user->id]));

        $response->assertRedirect(route('home'));
    }
}
