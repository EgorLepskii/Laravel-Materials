<?php

namespace App\Http\Requests;

use App\Http\Controllers\CategoryController;
use App\Http\Controllers\TagController;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class UpdateTagRequest extends FormRequest
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
        $currentTagId = $request->input('tag_id');

        return [
            'name' => [
                'required',
                sprintf('max:%s', TagController::MAX_NAME_LENGTH),
                Rule::unique('tags', 'name')->ignore($currentTagId)
            ],
        ];
    }
}
