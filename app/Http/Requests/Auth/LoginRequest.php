<?php

namespace App\Http\Requests\Auth;

use Illuminate\Foundation\Http\FormRequest;

class LoginRequest extends FormRequest
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
            'login'    => 'required',
            'password' => 'required|min:6|string',
        ];
    }

    protected function prepareForValidation()
    {
        $login_type = filter_var($this->input('login'), FILTER_VALIDATE_EMAIL )
            ? 'email'
            : 'username';

        $this->merge([
            $login_type => $this->input('login')
        ]);
    }
}
