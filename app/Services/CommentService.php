<?php

namespace App\Services;

use Illuminate\Support\Facades\DB;
use App\Models\Comment;

class CommentService
{
    public function getAllByPostId($postId, $loggedUserId)
    {
        $comments = Comment::select([
            'c.id AS comment_id',
            'c.description AS description',
            'c.votes_amount AS votes_amount',
            'c.user_id AS comment_user_id',
            'c.post_id AS comment_post_id',
            'u.name AS comment_user_name',
            'vc.user_id AS vote_user_id',
            'vc.vote_type_id AS vote_type_id',
        ])
            ->from('comments AS c')
            ->join('users AS u', 'c.user_id', '=', 'u.id')
            ->leftJoin('comments_votes AS vc', function ($join) use ($loggedUserId) {
                $join->on('c.id', '=', 'vc.comment_id')->where('vc.user_id', '=', $loggedUserId);
            })
            ->where('c.post_id', '=', $postId)
            ->orderByDesc('votes_amount')
            ->get();

        return $comments;
    }
}
