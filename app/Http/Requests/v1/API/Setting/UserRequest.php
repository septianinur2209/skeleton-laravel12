<?php

namespace App\Http\Requests\v1\API\Setting;

use App\Rules\UniqueWithCaseSensitive;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use App\Traits\MainTrait;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

class UserRequest extends FormRequest
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
            'email'     => ['nullable','string', 'max:255'],
            'name'      => ['nullable','string', 'max:255'],
            'status'    => ['nullable','boolean'],
        ];

        if (request()->routeIs('setting.user.create')) {
            
            $unique = new UniqueWithCaseSensitive('users', 'email', null, 'Email'); 

            array_push($rules["email"], $unique);
            array_push($rules["email"], 'required');
            array_push($rules["name"], 'required');

        }

        if (request()->routeIs('setting.user.edit')) {
            
            $unique = new UniqueWithCaseSensitive('users', 'email', $this->id, 'Email');

            array_push($rules["email"], $unique);
            array_push($rules["email"], 'required');
            array_push($rules["name"], 'required');
            array_push($rules["status"], 'required');

        }

        if (request()->routeIs('setting.user.update-status')) {

            array_push($rules["status"], 'required');

        }

        if(request()->routeIs('setting.user.show')) {

            $rules["type"] = [
                Rule::in(
                    "dropdown",
                    "table"
                )
            ];
            
            $rules["column"] = [
                "required_if:type,dropdown",
                Rule::in(
                    "name",
                    "email",
                    "status"
                )
            ];

        }

        return $rules;
    }

    protected function failedValidation(Validator $validator)
    {
        $response = $this->sendError($validator->errors(), 422);
        throw new HttpResponseException($response);
    }
}
