<?php

namespace App\Http\Requests\Provider;

use App\Models\Provider;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ProviderStoreRequest extends FormRequest
{
    protected $errorBag = 'providerStoreBag';

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\Rule|array|string>
     */
    public function rules(): array
    {
        return [
            'code'          => ['nullable', 'max:255', Rule::unique(Provider::class)],
            'name'          => ['required', 'string', 'max:255'],
            'api_url'       => ['required', 'string', 'url', Rule::unique(Provider::class)],
            'active'        => ['required', 'bool'],
        ];
    }
}
