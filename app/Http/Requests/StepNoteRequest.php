<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StepNoteRequest extends FormRequest
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
            'date' => 'required|date_format:d/m/Y|',
            'step_id' => 'required'

        ];
    }

    public function messages()
    {
        return [
            'number.integer' => 'Số phiếu phải là dạng số',
            'date.date_format' => 'Không đúng định dạng ngày',
            'step_id.required' => 'Chưa chọn công đoạn'
        ];
    }

    public function withValidator($validator)
    {
        $validator->after(function ($validator) {

        });
    }
}
