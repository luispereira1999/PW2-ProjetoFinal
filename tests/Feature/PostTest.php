<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use Database\Seeders\DatabaseSeeder;
use App\Models\Post;
use App\Models\User;

class PostTest extends TestCase
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


    /**
     * Ir para a página de um post específico.
     *
     * @test
     */
    public function test_posts_route()
    {
        $user = User::first();
        $this->actingAs($user);  // autentica utilizador

        $postId = Post::first()->id;
        $response = $this->get(route('posts', ['postId' => $postId]));

        $response->assertStatus(200);
        $response->assertViewIs('post');
        $response->assertViewHas('post');
        $response->assertViewHas('comments');
        $response->assertViewHas('loggedUserId');
    }


    /**
     * Criar um novo post.
     *
     * @test
     */
    public function test_posts_create_route()
    {
        $user = User::first();
        $this->actingAs($user);  // autentica utilizador

        $data = [
            'title' => 'Título',
            'description' => 'Descrição'
        ];

        $response = $this->post(route('posts.create'), $data);

        $response->assertStatus(201);
    }


    /**
     * Atualizar um post.
     *
     * @test
     */
    public function test_posts_update_route()
    {
        $post = Post::first();

        $user = $post->user;
        $this->actingAs($user);  // autentica utilizador

        $data = [
            'title' => 'Título atualizado',
            'description' => 'DescriÇão atualizadA'
        ];

        $response = $this->patch(route('posts.update', ['postId' => $post->id]), $data);

        $response->assertStatus(200);
    }


    /**
     * Atualizar um voto de um post.
     *
     * @test
     */
    public function test_posts_vote_route()
    {
        $user = User::first();
        $this->actingAs($user);  // autentica utilizador

        $postId = Post::first()->id;

        $data = [
            'voteTypeId' => 1
        ];

        $response = $this->patch(route('posts.vote', ['postId' => $postId]), $data);

        $response->assertStatus(200);
    }


    /**
     * Remover um post.
     *
     * @test
     */
    public function test_posts_delete_route()
    {
        $post = Post::first();

        $user = $post->user;
        $this->actingAs($user);  // autentica utilizador

        $response = $this->delete(route('posts.delete', ['postId' => $post->id]));

        $response->assertRedirect();
    }
}
