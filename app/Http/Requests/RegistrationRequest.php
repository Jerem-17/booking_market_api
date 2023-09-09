<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class RegistrationRequest extends FormRequest
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
    public function rules()
    {
        return [
            'nom' =>"string|min:2|required" ,
            'prenom' =>"string|min:2|required" ,
            "telephone" => "string|required",
            "email" =>"string|email",
            "lat" =>"string",
            "lng" =>"string",
            "password" =>"string|min:6|required",
        ];
    }
}
