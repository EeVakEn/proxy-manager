<?php

namespace App\Http\Requests;

use App\Enums\ProxyType;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateProxyRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'host'     => ['sometimes', 'string', 'max:255'],
            'port'     => ['sometimes', 'integer', 'min:1', 'max:65535'],
            'type'     => ['sometimes', Rule::enum(ProxyType::class)],
            'login'    => ['nullable', 'string', 'max:255'],
            'password' => ['nullable', 'string', 'max:255'],
        ];
    }
}