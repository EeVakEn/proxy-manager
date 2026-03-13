<?php

namespace App\Http\Requests;

use App\Enums\ProxyType;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreProxyRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'host'     => ['required', 'string', 'max:255'],
            'port'     => ['required', 'integer', 'min:1', 'max:65535',
                Rule::unique('proxies')->where('host', $this->input('host')),
            ],
            'type'     => ['required', Rule::enum(ProxyType::class)],
            'login'    => ['nullable', 'string', 'max:255'],
            'password' => ['nullable', 'string', 'max:255', 'required_with:login'],
        ];
    }

    public function messages(): array
    {
        return [
            'port.unique' => 'Proxy ' . $this->input('host') . ':' . $this->input('port') . ' already exists.',
        ];
    }
}
