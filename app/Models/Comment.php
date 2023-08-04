<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * Esta classe representa o modelo para a tabela de comentários na base de dados.
 */
class Comment extends Model
{
    use HasFactory;

    public $timestamps = false;

    protected $fillable = [
        'id',
        'description',
        'votes_amount',
        'user_id',
        'post_id'
    ];

    public function votes()
    {
        return $this->hasMany(CommentVote::class, 'comment_id', 'id');
    }
}
