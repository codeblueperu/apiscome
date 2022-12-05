<?php

namespace App\Http\Requests\Caja;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

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
            'descripcion_caja'=>'required|string|max:50',
            'fecha_apertura'=>'required|date',
            'fecha_cierre'=>'nullable|date',
            'monto_inicial'=>'required|numeric',
            'monto_efectivo'=>'nullable|numeric',
            'monto_digital'=>'nullable|numeric',
            'monto_gasto'=>'nullable|numeric',
            'monto_total'=>'nullable|numeric',
            'estado'=>'nullable',
            'cod_sucursal'=>'nullable',
            'cod_usuario'=>'nullable'
        ];
    }
    public function failedValidation(Validator $validator)
    {
        throw new HttpResponseException(response()->json([
            'error'   => true,
            'message'   => 'Error al intentar aperturar tu caja.',
            'data'      => $validator->errors()
        ],409));
    }
}
