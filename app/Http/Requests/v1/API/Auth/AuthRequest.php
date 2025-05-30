<?php

namespace App\Http\Requests\v1\API\Auth;

use App\Rules\UniqueWithCaseSensitive;
use Illuminate\Foundation\Http\FormRequest;
use App\Traits\MainTrait;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Validation\Rules\Password;

class AuthRequest extends FormRequest
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
            'email'     => ['required','string'],
            'name'      => ['nullable','string'],
            'password'  => ['nullable', Password::min(8)],
        ];

        if (request()->routeIs('auth.register')) {
            
            $unique = new UniqueWithCaseSensitive('users', 'email', null, 'Email');

            array_push($rules["name"], 'required');
            array_push($rules["email"], $unique);
            array_push($rules["password"], 'required');

        }

        if (request()->routeIs('auth.login')) {

            array_push($rules["password"], 'required');

        }

        if (request()->routeIs('auth.reset-password')) {

            array_push($rules["token"], 'required');
            array_push($rules["password"], 'required');
            array_push($rules["password"], 'confirmed');
            
        }

        return $rules;
    }

    protected function failedValidation(Validator $validator)
    {
        $response = $this->sendError($validator->errors(), 422);
        throw new HttpResponseException($response);
    }
}
