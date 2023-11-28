<?php

namespace App\Http\Requests\User;

use App\Enums\UserRoleTypeCode;
use App\Models\User;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class UserStoreRequest extends FormRequest
{
    protected $errorBag = 'userStoreBag';

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\Rule|array|string>
     */
    public function rules(): array
    {
        return [
            'name'          => ['required', 'string', 'max:255'],
            'username'      => ['required', 'string', 'lowercase', 'max:255', Rule::unique(User::class)],
            'backend_url'   => ['required', 'string', 'url', Rule::unique(User::class)],
            'password'      => ['required', 'string',  'min:6'],
            'role_code'     => [Rule::in([UserRoleTypeCode::CLIENT->value])],
        ];
    }

    protected function prepareForValidation()
    {
        $this->merge([
            'role_code' => UserRoleTypeCode::CLIENT->value,
        ]);
    }

    protected function passedValidation()
    {
        $this->replace([
            'password'  => Hash::make($this->password),
        ]);
    }
}
