<?php

namespace App\Http\Requests\Integration;

use App\Models\Integration;
use App\Models\Provider;
use App\Models\User;
use App\Rules\Active;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class IntegrationUpdateRequest extends FormRequest
{
    protected $errorBag = 'updateIntegrations';

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
        /** @var User */
        $user = auth()->user();

        return [
            'id' => [
                Rule::exists(Integration::class, 'id'),
            ],
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
            'api_url' => [
                'required',
                'string',
                'url',
            ],
            'api_token' => [
                'required',
            ],
            'active' => [
                'required',
                'bool',
            ],
        ];
    }
}
