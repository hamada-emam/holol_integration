<?php

namespace App\Http\Requests\Integration;

use App\Models\Provider;
use App\Models\User;
use App\Rules\Active;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class IntegrationStoreRequest extends FormRequest
{
    protected $errorBag = 'integrationStoreBag';

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\Rule|array|string>
     */
    public function rules(): array
    {
        /** @var User */
        $user = auth()->user();

        return [
            'user_id' => [
                Rule::requiredIf($user->isAdmin),
                Rule::prohibitedIf(!$user->isAdmin),
                Rule::exists(User::class, 'id'),
            ],
            'provider_id' => [
                'required',
                new Active(Provider::class),
                Rule::exists(Provider::class, 'id'),
            ],
            'provider_url' => [
                'required',
                'string',
                'url',
            ],
            'provider_username' => [
                'required',
            ],
            'user_username' => [
                'required',
            ],
            'provider_password' => [
                'required',
            ],
            'user_password' => [
                'required',
            ],
            'active' => [
                'required',
                'bool',
            ],
        ];
    }
}
