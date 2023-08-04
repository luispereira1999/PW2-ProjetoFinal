<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * Esta classe representa o modelo para a tabela de votos dos posts na base de dados.
 */
class PostVote extends Model
{
    use HasFactory;

    protected $table = 'posts_votes';

    public $timestamps = false;

    protected $fillable = [
        'post_id',
        'user_id',
        'vote_type_id'
    ];
}
