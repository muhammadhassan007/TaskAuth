<?php

namespace App\Http\Requests;

use Illuminate\Validation\Rule;
use Illuminate\Foundation\Http\FormRequest;
use App\Http\Requests\CustomRequest;



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
     * @return array
     */
    public function rules()
    {
        if ($this->route()->getName() == 'LoginUser')
            return [
                'email'    => 'bail|required|email',
                'password' => 'bail|required|min:6',
            ];
        else
            return [
                'type'     => 'nullable|in:guest',
                'email'    => [
                    Rule::requiredIf(!$this->type),
                    'exists:users,email',
                    'email'
                ],
                'password' => [Rule::requiredIf(!$this->type), 'min:6'],
            ];
    }
}
