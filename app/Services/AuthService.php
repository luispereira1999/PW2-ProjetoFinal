<?php

namespace App\Services;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class authService
{
    public function getUser()
    {
        $user = Auth::user();
        return $user;
    }

    public function getUserId()
    {
        $user = Auth::user();

        if ($user) {
            return $user->id;
        } else {
            return -1;
        }
    }
}
