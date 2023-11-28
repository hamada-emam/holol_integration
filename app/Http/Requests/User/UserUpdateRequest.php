<?php

namespace App\Http\Requests\User;

use App\Enums\UserRoleTypeCode;
use App\Models\User;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class UserUpdateRequest extends FormRequest
{
    protected $errorBag = 'updateUsers';

    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\Rule|array|string>
     */
    public function rules(): array
    {
        $rules = [
            'id'            => ['required', 'numeric', 'exists:users,id'],
            'name'          => ['required', 'string', 'max:255'],
            'username'      => ['required', 'string', 'lowercase', 'max:255', Rule::unique(User::class)->ignore($this->route('user'))],
            'backend_url'   => ['required', 'string', 'url', Rule::unique(User::class)->ignore($this->route('user'))],
            'role_code'     => [Rule::in([UserRoleTypeCode::CLIENT->value])],
        ];
        if ($this->password) $rules['password'] =  ['password' => ['string',  'min:6']];
        return $rules;
    }

    protected function prepareForValidation()
    {
        $this->merge([
            'role_code' => UserRoleTypeCode::CLIENT->value,
        ]);
    }
}
