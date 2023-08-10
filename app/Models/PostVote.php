<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Post;
use App\Models\User;
use App\Models\VoteType;

/**
 * Esta classe representa o modelo para a tabela de votos dos posts na base de dados.
 */
class PostVote extends Model
{
    use HasFactory;

    protected $table = 'posts_votes';

    protected $primaryKey = ['post_id', 'user_id'];

    public $incrementing = false;

    public $timestamps = false;

    protected $fillable = [
        'post_id',
        'user_id',
        'vote_type_id'
    ];

    public function post()
    {
        return $this->belongsTo(Post::class);
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
