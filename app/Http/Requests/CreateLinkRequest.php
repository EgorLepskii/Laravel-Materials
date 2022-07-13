<?php

namespace App\Http\Requests;

use App\Http\Controllers\LinkController;
use GuzzleHttp\Psr7\Request;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Lang;
use Illuminate\Validation\Rule;

class CreateLinkRequest extends FormRequest
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
        $materialId = $request->input('material_id');

        return [
            'sign' => [
                Rule::unique('links')->where('material_id', $materialId)->whereNot('sign', ""),
                sprintf('max:%s', LinkController::MAX_LINK_SIGN)
            ],
            'url' => sprintf('required|max:%s|url', LinkController::MAX_LINK_URL),
            'material_id' => 'required|exists:materials,id',

        ];
    }

    public function messages()
    {
        return [
            'required' => 'Заполните поле со ссылкой',
            'url' => Lang::get('validation.url')
        ];
    }
}
