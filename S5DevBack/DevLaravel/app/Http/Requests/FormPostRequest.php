<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class FormPostRequest extends FormRequest
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
            /* 'name' => ['required', 'string','max:20', Rule::unique('email')->ignore($this->route()->parameter('sauce'))],
            'manufacturer' => ['required', 'string','max:255'],
            'description' => ['required', 'string'],
            'mainPepper' => ['required', 'string','max:255'],
            'imageUrl' => ['required', 'url', 'regex:/^https?:\/\/.*\.(png|jpg|jpeg|gif|bmp)$/i'],
            'heat' => ['required', 'integer', 'min:1', 'max:10'], */
        ];
    }

    /**
     * Get the validation error messages that apply to the request.
     *
     * @return array
     */
    public function messages()
    {
        return [
            /* 'name.required' => 'Le champ nom est requis.',
            'name.max' => 'Le champ nom ne doit pas dépasser 20 caractères.',
            'name.unique' => 'Ce nom de sauce est déjà utilisé.',
            'manufacturer.required' => 'Le champ fabricant est requis.',
            'manufacturer.max' => 'Le champ fabricant ne doit pas dépasser 255 caractères.',
            'description.required' => 'Le champ description est requis.',
            'mainPepper.required' => 'Le champ ingrédient principal est requis.',
            'mainPepper.max' => 'Le champ ingrédient principal ne doit pas dépasser 255 caractères.',
            'imageUrl.required' => 'Le champ URL de l\'image est requis.',
            'imageUrl.url' => 'Le champ URL de l\'image doit être une URL valide.',
            'imageUrl.regex' => 'Le champ URL de l\'image doit être une image valide (png, jpg, jpeg, gif, bmp).',
            'heat.required' => 'Le champ niveau de piquant est requis.',
            'heat.integer' => 'Le champ niveau de piquant doit être un entier.',
            'heat.min' => 'Le champ niveau de piquant doit être au moins 1.',
            'heat.max' => 'Le champ niveau de piquant ne doit pas dépasser 10.', */
        ];
    }
}
