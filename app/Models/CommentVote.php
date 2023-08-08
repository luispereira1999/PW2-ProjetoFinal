<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * Esta classe representa o modelo para a tabela de votos dos comentários na base de dados.
 */
class CommentVote extends Model
{
    use HasFactory;

    protected $table = 'comments_votes';

    protected $primaryKey = ['comment_id', 'user_id'];

    public $incrementing = false;

    public $timestamps = false;

    protected $fillable = [
        'comment_id',
        'user_id',
        'vote_type_id'
    ];

    public function comment()
    {
        return $this->belongsTo(Comment::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function voteType()
    {
        return $this->belongsTo(VoteType::class);
    }
}
