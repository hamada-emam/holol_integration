<?php

namespace App\Http\Requests\Provider;

use Illuminate\Foundation\Http\FormRequest;

class ProviderDeleteRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }
    // TODO throw validation error in ui 
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\Rule|array|string>
     */
    public function rules(): array
    {
        return [
            'id' => 'required|numeric|exists:users,id',
        ];
    }
}
