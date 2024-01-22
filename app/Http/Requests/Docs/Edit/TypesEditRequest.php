<?php

namespace App\Http\Requests\Docs\Edit;

use Illuminate\Foundation\Http\FormRequest;

class TypesEditRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'title' => ['max:30'],
            'abbreviation' => ['max:5']
        ];
    }

    public function messages(): array
    {
        return [
            'max' => 'O campo :attribute deve possuir no máximo :max caracteres',
        ];
    }
}