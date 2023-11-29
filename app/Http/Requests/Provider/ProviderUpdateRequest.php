<?php

namespace App\Http\Requests\Provider;

use App\Models\Provider;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ProviderUpdateRequest extends FormRequest
{
    protected $errorBag = 'updateProviders';

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
        return [
            'id'            => ['required', 'numeric', 'exists:providers,id'],
            'code'          => ['required', 'max:255', Rule::unique(Provider::class)->ignore($this->id)],
            'name'          => ['required', 'string', 'max:255'],
            'api_url'       => ['required', 'string', 'url', Rule::unique(Provider::class)->ignore($this->id)],
            'active'        => ['required', 'bool'],
        ];
    }
}
