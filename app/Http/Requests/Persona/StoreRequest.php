<?php

namespace App\Http\Requests\Persona;

use Illuminate\Support\Facades\Auth;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Contracts\Validation\Validator;

class StoreRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return Auth::check();
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
            'email'=>'required',
            'numero_telefono'=>'required',
            'avatar'=>'nullable',
            'cod_usuario'=>'required',
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
