<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class FileRequest extends FormRequest
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
            'doc'           => 'required|file|mimes:pdf,doc,docx,zip|max:20480',
            'modifier_id'   => 'required|exists:employees,guid',
            'name'          => 'nullable|string',
        ];
    }
}
