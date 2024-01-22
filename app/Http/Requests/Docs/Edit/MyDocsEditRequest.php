<?php

namespace App\Http\Requests\Docs\Edit;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class MyDocsEditRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'type_id' => ['exists:types,id'],
            'file' => ['string'],
        ];
    }

    public function messages(): array
    {
        return [
            'exists' => 'O campo :attribute não existe em nossos registros internos.',
            'string' => 'O campo :attribute precisa conter uma string válida.'
        ];
    }

    public function prepareForValidation()
    {
        $input = $this->all();

        // Salvando o user logado no campo
        $input['user_id'] = Auth::user()->id;

        $this->replace($input);
    }
}
