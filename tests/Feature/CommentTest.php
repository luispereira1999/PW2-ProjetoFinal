<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use Database\Seeders\DatabaseSeeder;
use App\Models\Comment;
use App\Models\Post;
use App\Models\User;

class CommentTest extends TestCase
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
     * Criar um novo comentário.
     *
     * @test
     */
    public function test_comments_create_route()
    {
        $post = Post::first();

        $user = $post->user;
        $this->actingAs($user);  // autentica utilizador

        $data = [
            'title' => 'Primeiro título',
            'description' => 'Primeira descrição'
        ];

        $response = $this->post(route('comments.create', ['postId' => $post->id]), $data);

        $response->assertStatus(201);
    }


    /**
     * Atualizar um comentário.
     *
     * @test
     */
    public function test_comments_update_route()
    {
        $comment = Comment::first();

        $user = $comment->user;
        $this->actingAs($user);  // autentica utilizador

        $data = [
            'title' => 'Título atualizado',
            'description' => 'DescriÇão atualizadA'
        ];

        $response = $this->patch(route('comments.update', ['commentId' => $comment->id]), $data);

        $response->assertStatus(200);
    }


    /**
     * Atualizar um voto de um comentário.
     *
     * @test
     */
    public function test_comments_vote_route()
    {
        $user = User::first();
        $this->actingAs($user);  // autentica utilizador

        $commentId = Comment::first()->id;

        $data = [
            'voteTypeId' => 1
        ];

        $response = $this->patch(route('comments.vote', ['commentId' => $commentId]), $data);

        $response->assertStatus(200);
    }


    /**
     * Remover um comentário.
     *
     * @test
     */
    public function test_comments_delete_route()
    {
        $comment = Comment::first();

        $post = $comment->post;

        $user = $comment->user;
        $this->actingAs($user);  // autentica utilizador

        $response = $this->delete(route('comments.delete', ['commentId' => $comment->id, 'postId' => $post->id]));

        $response->assertRedirect();
    }
}
