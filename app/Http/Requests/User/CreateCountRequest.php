<?php

namespace App\Http\Requests\User;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Support\Facades\Auth;

class CreateCountRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;//Auth::check();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'nombres'=>'required|unique:tb_personas,nombres',
            'numero_documento'=>'required|unique:tb_personas,numero_documento',
            'direccion'=>'nullable',
            'numero_telefono'=>'required',
            'avatar'=>'nullable',
            'email'=> 'required|email|unique:users,email',
            'password' => 'required',
            'cod_cargo'=>'required',
            'cod_tipo_documento'=>'required',
        ];
    }

    public function failedValidation(Validator $validator)
    {
        throw new HttpResponseException(response()->json([
            'error'   => true,
            'message'   => 'Error al intentar registrar una nueva persona.',
            'data'      => $validator->errors()
        ],409));
    }
}
