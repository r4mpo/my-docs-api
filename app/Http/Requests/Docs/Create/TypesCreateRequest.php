<?php

namespace App\Http\Requests\Docs\Create;

use Illuminate\Foundation\Http\FormRequest;

class TypesCreateRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'title' => ['required', 'max:30'],
            'abbreviation' => ['required', 'max:5']
        ];
    }

    public function messages(): array
    {
        return [
            'required' => 'O campo :attribute é obrigatório e, aparentemente, não foi devidamente enviado.',
            'max' => 'O campo :attribute deve possuir no máximo :max caracteres',
        ];
    }
}
