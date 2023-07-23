<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\DB;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    public $timestamps = false;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'id',
        'name',
        'password',
        'email',
        'first_name',
        'last_name',
        'city',
        'country'
    ];

    public static function one($userId)
    {
        $user = DB::table('users')
            ->select('users.id', 'users.name', 'users.password', 'users.email', 'users.first_name', 'users.last_name', 'users.city', 'users.country')
            ->where('users.id', '=', $userId)
            ->first();

        return $user;
    }
}
