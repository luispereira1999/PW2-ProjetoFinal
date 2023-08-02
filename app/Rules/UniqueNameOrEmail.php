<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;
use App\Services\AuthService;
use App\Models\User;

class UniqueNameOrEmail implements Rule
{
    protected $authService;
    protected $customAttribute;

    public function __construct($customAttribute, AuthService $authService)
    {
        $this->authService = $authService;
        $this->customAttribute = $customAttribute;
    }


    /**
     * Definir regra de validação para determinar se algum nome de utilizador ou email já existe na base de dados.
     *
     * @param  string  $attribute
     * @param  mixed  $value
     * @return bool
     */
    public function passes($attribute, $value)
    {
        $loggedUserId = $this->authService->getUserId();

        // ignora a verificação para o utilizador atualmente com login
        return !User::where('id', '!=', $loggedUserId)
            ->where(function ($query) use ($value) {
                $query->where('name', $value)
                    ->orWhere('email', $value);
            })
            ->exists();
    }


    /**
     * Mensagem de erro da regra de validação.
     *
     * @return string
     */
    public function message()
    {
        if ($this->customAttribute == 'name') {
            $this->customAttribute = 'nome de utilizador';
        }

        return 'O ' . $this->customAttribute . ' já está em uso.';
    }
}
