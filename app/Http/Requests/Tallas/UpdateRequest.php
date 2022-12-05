<?php

namespace App\Http\Requests\Tallas;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Support\Facades\Auth;

class UpdateRequest extends FormRequest
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
            'talla'=>'required|min:1|max:10',
            'cantidad'=>'required|integer',
            'estado'=>'nullable',
            'cod_producto'=>'required|exists:tb_productos,cod_producto'
        ];
    }

    public function failedValidation(Validator $validator)
    {
        throw new HttpResponseException(response()->json([
            'error'   => true,
            'message'   => 'Error al intentar registrar la talla.',
            'data'      => $validator->errors()
        ],409));
    }
}
