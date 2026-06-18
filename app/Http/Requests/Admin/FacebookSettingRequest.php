<?php

namespace App\Http\Requests\Admin;

use App\Models\FacebookSetting;
use Illuminate\Foundation\Http\FormRequest;

class FacebookSettingRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()?->can('viewAny', FacebookSetting::class) ?? false;
    }

    public function rules(): array
    {
        return [
            'page_id' => ['required', 'string', 'max:255'],
            'access_token' => ['required', 'string', 'max:5000'],
            'auto_share_enabled' => ['boolean'],
        ];
    }
}
