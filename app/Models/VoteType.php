<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Esta classe representa o modelo para a tabela de tipos de votos na base de dados.
 */
class VoteType extends Model
{
    protected $table = 'vote_types';

    public $incrementing = false;

    public $timestamps = false;

    protected $fillable = [
        'title'
    ];
}
