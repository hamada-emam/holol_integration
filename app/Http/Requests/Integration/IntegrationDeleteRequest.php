<?php

namespace App\Http\Requests\Integration;

use Illuminate\Foundation\Http\FormRequest;

class IntegrationDeleteRequest extends FormRequest
{
    public function authorize()
    {
        // TODO throw validation error in ui 
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
            'id' => 'required|numeric|exists:integrations,id',
        ];
    }
}
