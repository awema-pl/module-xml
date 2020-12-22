<?php

namespace AwemaPL\Xml\User\Sections\Ceneosources\Http\Requests;

use AwemaPL\Xml\User\Sections\Ceneosources\Http\Requests\Rules\ValidUrl;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreCeneosource extends FormRequest
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
            'name' => 'required|string|max:255',
            'url' => ['required', 'string', 'max:255', new ValidUrl()],
        ];
    }


    /**
     * Get custom attributes for validator errors.
     *
     * @return array
     */
    public function attributes()
    {
        return [
            'name' => _p('xml::requests.user.ceneosource.attributes.name', 'name'),
            'url' => _p('xml::requests.user.ceneosource.attributes.url', 'website address'),
        ];
    }

    /**
     * Get the error messages for the defined validation rules.
     *
     * @return array
     */
    public function messages()
    {
        return [];
    }
}
