<?php

namespace App\Http\Requests\CuponProducto;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

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
            'numero_cupon'=>'required|min:5|max:20',
            'fecha_inicio'=>'required|date',
            'fecha_termino'=>'required|date',
            'stock'=>'required|integer',
            'valor_descuento'=>'required|numeric',
            'estado'=>'nullable',
            'cod_producto'=>'required|exists:tb_productos,cod_producto',
        ];
    }

    public function failedValidation(Validator $validator)
    {
        throw new HttpResponseException(response()->json([
            'error'   => true,
            'message'   => 'Error al intentar registrar un nuevo cupon.',
            'data'      => $validator->errors()
        ],409));
    }
}
