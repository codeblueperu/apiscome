<?php

namespace App\Http\Requests\Marca;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Support\Facades\Auth;

class StoreRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     * unique => |unique:tb_marcas,descripcion_marca
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
            'descripcion_marca'=>'required|max:35',
            'logo'=>'nullable|image|mimes:png,jpg,jpeg|max:3000',
            'estado'=>'nullable',
            'cod_categoria'=>'required|exists:tb_categorias,cod_categoria'
        ];
    }

    public function failedValidation(Validator $validator)
    {
        throw new HttpResponseException(response()->json([
            'error'   => true,
            'message'   => 'Error al intentar registrar una nueva marca.',
            'data'      => $validator->errors()
        ],409));
    }
}
