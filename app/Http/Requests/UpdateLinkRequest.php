<?php

namespace App\Http\Requests;

use App\Http\Controllers\LinkController;
use GuzzleHttp\Psr7\Request;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Lang;
use Illuminate\Validation\Rule;

class UpdateLinkRequest extends FormRequest
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
        $materialId = $request->input('materialId');
        $linkId = $request->input('linkId');

        return [
            'materialId' => 'required|exists:materials,id',
            'signUpdate' => [
                Rule::unique('links','sign')->where('material_id', $materialId)
                    ->whereNot('sign', "")->ignore($linkId),
                sprintf('max:%s', LinkController::MAX_LINK_SIGN)
            ],
            'urlUpdate' => sprintf('required|max:%s|url', LinkController::MAX_LINK_URL),
        ];
    }

    public function messages()
    {
        return [
            'required' => 'Нужно заполнить все поля',
            'url' => Lang::get('validation.url')

        ];
    }
}
