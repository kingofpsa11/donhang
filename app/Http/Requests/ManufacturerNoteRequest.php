<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ManufacturerNoteRequest extends FormRequest
{
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
    public function rules()
    {
        return [
            'number' => 'required|integer',
            'date' => 'required|date_format:d/m/Y'
        ];
    }

    public function messages()
    {
        return [
            'number.required' => 'Số phiếu phải là dạng số',
            'date.required' => 'Phải điền ngày',
            'date.date_format' => 'Định dạng ngày không đúng'
        ];
    }
}
