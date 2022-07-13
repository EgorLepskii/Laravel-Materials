<?php

namespace App\Http\Requests;

use App\Http\Controllers\CategoryController;
use App\Http\Controllers\MaterialController;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class UpdateCategoryRequest extends FormRequest
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
        $currentCategoryId = $request->input('category_id');

        return [
            'name' => [
                'required',
                sprintf('max:%s', CategoryController::MAX_NAME_LENGTH),
                Rule::unique('categories', 'name')->ignore($currentCategoryId)
            ],
        ];
    }
}
