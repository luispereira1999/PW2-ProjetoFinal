<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Post;
use App\Models\PostVote;

class PostController extends Controller
{
    /**
     * Display a listing of the posts.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $loggedUser = Auth::user();
        $loggedUserId = $loggedUser ? $loggedUser->id : -1;

        $posts = Post::allInHome($loggedUserId);

        return view('home', [
            'posts' => $posts,
            'loggedUserId' => $loggedUserId
        ]);
    }


    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }


    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }


    /**
     * Display the specified post.
     *
     * @param  int  $postId
     * @return \Illuminate\Http\Response
     */
    public static function show($postId)
    {
        $loggedUser = Auth::user();
        $loggedUserId = $loggedUser ? $loggedUser->id : -1;

        $post = Post::oneInPost($loggedUserId, $postId);

        return view('post', [
            'post' => $post,
            'loggedUserId' => $loggedUserId
        ]);
    }

    /**
     * Update the specified post data in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $postId
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $postId)
    {
        $request->validate([
            'title' => 'required',
            'description' => 'required',
        ], [
            'title.required' => 'O título é obrigatório.',
            'description.required' => 'A descrição é obrigatória.'
        ]);

        if (!Auth::check()) {
            return redirect()->route('auth')->with('error', 'Você precisa fazer login para atualizar seu perfil.');
        }

        $loggedUser = Auth::user();
        $loggedUserId = $loggedUser ? $loggedUser->id : -1;

        $post = Post::findOrFail($postId);

        // verificar se pertence ao utilizador com login
        if ($post->user_id != $loggedUserId) {
            return redirect()->route('auth')->with('error', 'Você precisa fazer login para atualizar seu perfil.');
        }

        $post->title = $request->input('title');
        $post->description = $request->input('description');

        $post->save();

        return redirect()->back()->with('success', 'Post atualizado com sucesso!')
            ->with('reload', true);
    }

    /**
     * Update the specified post data in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $postId
     * @return \Illuminate\Http\Response
     */
    public function vote(Request $request, $postId)
    {
        $request->validate([
            'voteTypeId' => 'required|in:1,2'
        ], [
            'voteTypeId.required' => 'O identificador do tipo de voto é obrigatório.',
            'voteTypeId.in' => 'O identificador do tipo de voto inválido.',
        ]);

        if (!Auth::check()) {
            return redirect()->route('auth')->with('error', 'Você precisa fazer login para realizar esta operação.');
        }

        $loggedUser = Auth::user();
        $loggedUserId = $loggedUser ? $loggedUser->id : -1;

        $post = Post::findOrFail($postId);

        if (!$post) {
            $stringsArray = ['Erro ao votar'];

            return response()->json($stringsArray);
        }

        Post::vote($postId, $request->input('voteTypeId'), $loggedUserId);

        $votesAmount = Post::where('id', $postId)->pluck('votes_amount')->first();

        return response()->json([
            'votesAmount' => $votesAmount
        ]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $postId
     * @return \Illuminate\Http\Response
     */
    public function destroy($postId)
    {
        $post = Post::find($postId);

        if (!$post) {
            return redirect()->back()->with('error', 'Post not found.');
        }

        $loggedUser = Auth::user();
        $loggedUserId = $loggedUser ? $loggedUser->id : -1;

        // verificar se pertence ao utilizador com login
        if ($post->user_id != $loggedUserId) {
            return redirect()->route('auth')->with('error', 'Você precisa fazer login para atualizar seu perfil.');
        }

        $post->delete();

        return redirect()->back()->with('success', 'Post deleted successfully.');
    }
}
