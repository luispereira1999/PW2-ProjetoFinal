<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Comment;
use App\Models\CommentVote;

class CommentsVotesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $combinations = [];
        $numberOfCombinations = 20;
        $current = 0;

        // obtém as combinações únicas para as chaves primárias de cada registo
        while ($current < $numberOfCombinations) {
            $commentId = Comment::inRandomOrder()->first()->id;
            $userId = User::inRandomOrder()->first()->id;

            $combination = "{$commentId}_{$userId}";

            $exists = CommentVote::where('comment_id', $commentId)
                ->where('user_id', $userId)
                ->exists();

            if (!$exists) {
                if (!in_array($combination, $combinations)) {
                    $combinations[] = $combination;
                }
            }

            $current++;
        }

        // cria os dados na base de dados
        foreach ($combinations as $combination) {
            [$commentId, $userId] = explode('_', $combination);

            CommentVote::factory()->createWithCommentAndUser($commentId, $userId);
        }

        // obtém os votos que pertencem aos comentários
        $votesWithComments = CommentVote::select('comment_id')
            ->groupBy('comment_id')
            ->get();

        // para cada comentário, atualiza a quantidade de votos desse comentário
        foreach ($votesWithComments as $comment) {
            $votesAmount = CommentVote::where('comment_id', $comment->post_id)
                ->count();

            Comment::Where('id', $comment->comment_id)
                ->update(['votes_amount' => $votesAmount]);
        }
    }
}
