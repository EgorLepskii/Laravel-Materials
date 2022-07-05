<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class LinkTagRequest extends FormRequest
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
     * @return array<string, mixed>
     */
    public function rules(Request $request)
    {
        $materialId = $request->input('materialId');

        return [
            'tag' => [
                'required',
                'exists:tags,id',
                Rule::unique('materials_tags', 'tag_id')->where('material_id', $materialId)
            ]
        ];
    }
}
