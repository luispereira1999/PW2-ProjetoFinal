<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use Illuminate\Support\Facades\Auth;
use App\Models\User;

class LoginTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Testa o login bem-sucedido.
     *
     * @return void
     */
    public function testLogin()
    {
        // credenciais de acesso
        $credentials = [
            'name' => 'Luisa',
            'user_or_email' => '123456'
        ];

        // Faça uma requisição POST para a rota de login com as credenciais válidas
        $response = $this->post('/auth/login', $credentials);



        // Verifique se o usuário foi redirecionado para a página inicial após o login
        // $response->assertRedirect('/');

        $user = Auth::user();

        // Verifique se o usuário está autenticado
        $this->assertAuthenticatedAs($user);
    }
}
