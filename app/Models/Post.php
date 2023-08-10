<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\User;

/**
 * Esta classe representa o modelo para a tabela de posts na base de dados.
 */
class Post extends Model
{
    use HasFactory;

    protected $table = 'posts';

    public $incrementing = true;

    public $timestamps = false;

    protected $fillable = [
        'title',
        'description',
        'date',
        'votes_amount',
        'comments_amount',
        'user_id'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
