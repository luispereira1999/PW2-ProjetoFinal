<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;
use App\Models\User;

class UniqueNameOrEmail implements Rule
{
    protected $customAttribute;

    public function __construct($customAttribute)
    {
        $this->customAttribute = $customAttribute;
    }

    /**
     * Definir regra de validação.
     *
     * @param  string  $attribute
     * @param  mixed  $value
     * @return bool
     */
    public function passes($attribute, $value)
    {
        return !User::where('name', $value)->orWhere('email', $value)->exists();
    }

    /**
     * Mensagem de erro da regra de validação.
     *
     * @return string
     */
    public function message()
    {
        if ($this->customAttribute == 'name') {
            $this->customAttribute = 'nome';
        }

        return 'O ' . $this->customAttribute . ' já está em uso.';
    }
}
