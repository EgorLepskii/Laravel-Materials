<?php

namespace App\Http\Requests;

use App\Http\Controllers\MaterialController;
use GuzzleHttp\Psr7\Request;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Lang;
use Illuminate\Validation\Rule;

class UpdateMaterialRequest extends FormRequest
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
    public function rules(\Illuminate\Http\Request $request)
    {
        $currentMaterialId = $request->input('id');

        return [
            'name' => [
                'required',
                sprintf('max:%s', MaterialController::MAX_NAME_LENGTH),
                Rule::unique('materials', 'name')->ignore($currentMaterialId)
            ],
            'authors' => sprintf('max:%s', MaterialController::MAX_AUTHORS_TEXT_LENGTH),
            'description' => sprintf('max:%s', MaterialController::MAX_DESCRIPTION_LENGTH),
            'type_id' => 'required|integer|exists:types,id',
            'category_id' => 'required|integer|exists:categories,id'
        ];
    }

    public function messages()
    {
        return [
            'required' => Lang::get('materialValidation.required'),
            'unique' => Lang::get('materialValidation.unique'),
            'integer' => Lang::get('materialValidation.int'),
        ];
    }
}
