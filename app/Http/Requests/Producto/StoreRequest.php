<?php

namespace App\Http\Requests\Producto;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Support\Facades\Auth;

class StoreRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return
        Auth::check();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'code_barra'=>'nullable|max:12',
            'titulo_producto'=>'required|min:3|max:30',
            'descripcion_corta'=>'nullable|max:60',
            'descripcion_larga'=>'nullable|max:100',
            'stock'=>'required|integer|min:1',
            'precio_unitario'=>'required|numeric',
            'precio_mayor'=>'required|numeric',
            'estado'=>'nullable',
            'cod_categoria'=>'exists:tb_categorias,cod_categoria',
            'cod_marca'=>'exists:tb_marcas,cod_marca',
            'precio_compra'=>'nullable|numeric',
            'numero_lote'=>'nullable|max:9',
            'fecha_compra'=>'nullable',
            'fecha_elaboracion'=>'nullable',
            'fecha_caduca'=>'nullable',
            'stock_compra'=>'nullable'
        ];
    }

    public function failedValidation(Validator $validator)
    {
        throw new HttpResponseException(response()->json([
            'error'   => true,
            'message'   => 'Error al intentar registrar un nuevo producto.',
            'data'      => $validator->errors()
        ],409));
    }
}
