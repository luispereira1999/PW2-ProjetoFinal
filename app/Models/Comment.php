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

    protected $table = 'comments';

    public $incrementing = true;

    public $timestamps = false;

    protected $fillable = [
        'description',
        'votes_amount',
        'user_id',
        'post_id'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function post()
    {
        return $this->belongsTo(Post::class);
    }
}
