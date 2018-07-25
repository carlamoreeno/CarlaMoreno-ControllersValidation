<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

use Illuminate\Foundation\Http\FormRequest;

class CriarCliente extends FormRequest
{
    //Validation Failure
    protected function failedValidation(Validator $validator)
    {
        throw new HttpResponseException(response()->json($validator->errors(),
        422));
    }

    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
//Regras de Validação
    public function rules()
    {
        return [
            'RG' => 'required|string',
            'CPF' => 'required|cpf',
            'nome' => 'required|alpha',
            'telefone' => 'required|telefone',
            'endereco' => 'required|string',
            'email' => 'required|string'
        ];
    }
}
