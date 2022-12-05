<?php

namespace App\Http\Requests\ImagenProducto;

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
            'image_name'=>'required|image|mimes:png,jpg,jpeg|max:3000',
            'orden'=>'required',
            'estado'=>'nullable',
            'cod_producto'=>'required|exists:tb_productos,cod_producto',
        ];
    }

    public function failedValidation(Validator $validator)
    {
        throw new HttpResponseException(response()->json([
            'error'   => true,
            'message'   => 'Error al intentar carga la imagen.',
            'data'      => $validator->errors()
        ],409));
    }
}
