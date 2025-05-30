<?php

namespace App\Http\Requests\v1\API\Setting;

use App\Rules\UniqueWithCaseSensitive;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Password;
use App\Traits\MainTrait;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

class ProfileRequest extends FormRequest
{
    use MainTrait;
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {

        $rules = [
            'email'             => ['nullable','string', 'max:255'],
            'name'              => ['nullable','string', 'max:255'],
            'password'          => ['nullable','confirmed', Password::min(8)],
            'current_password'  => ['nullable'],
            'status'            => ['nullable','boolean'],
            'picture'           => ['nullable', 'image', 'mimes:jpeg,png,jpg', 'max:2048'],
        ];

        if (request()->routeIs('setting.profile.update-password')) {

            array_push($rules["current_password"], 'required');
            array_push($rules["current_password"], 'string');
            array_push($rules["current_password"], 'min:8');

            array_push($rules["password"], 'required');

        }

        if (request()->routeIs('setting.profile.edit')) {
            
            $unique = new UniqueWithCaseSensitive('users', 'email', $this->id, 'Email');

            array_push($rules["email"], $unique);

        }

        if (request()->routeIs('setting.profile.update-profile-photo')) {

            array_push($rules["picture"], 'required');

        }

        return $rules;
    }

    protected function failedValidation(Validator $validator)
    {
        $response = $this->sendError($validator->errors(), 422);
        throw new HttpResponseException($response);
    }
}
