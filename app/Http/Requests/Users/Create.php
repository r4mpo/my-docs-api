<?php

namespace App\Http\Requests\Users;

use App\Models\User;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Hash;

class Create extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:' . User::class],
            'password' => ['required'],
        ];
    }

    public function messages()
    {
        return [
            'name.required' => 'O nome de usuário é obrigatório.',
            'name.max' => 'O nome de usuário deve conter no máximo :max caracteres.',

            'email.required' => 'O e-mail de usuário é obrigatório.',
            'email.email' => 'O e-mail de usuário não está em um formato válido.',
            'email.max' => 'O e-mail de usuário deve conter no máximo :max caracteres.',
            'email.unique' => 'O e-mail do usuário não está disponível para o uso.',

            'password' => 'A senha do usuário é um campo obrigatório.',
        ];
    }

    public function prepareForValidation()
    {
        $input = $this->all();

        if ($this->has('password'))
            $input['password'] = Hash::make($this->get('password'));

        $this->replace($input);
    }
}