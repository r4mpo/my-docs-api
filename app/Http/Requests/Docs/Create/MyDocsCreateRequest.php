<?php

namespace App\Http\Requests\Docs\Create;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class MyDocsCreateRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'type_id' => ['required', 'exists:types,id'],
            'file' => ['required', 'file']
        ];
    }

    public function messages(): array
    {
        return [
            'required' => 'O campo :attribute é obrigatório e, aparentemente, não foi devidamente enviado.',
            'exists' => 'O campo :attribute não existe em nossos registros internos.',
            'file' => 'O campo :attribute precisa conter um arquivo válido.'
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
