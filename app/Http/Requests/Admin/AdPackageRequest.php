<?php

namespace App\Http\Requests\Admin;

use App\Models\AdPackage;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class AdPackageRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()?->can('viewAny', AdPackage::class) ?? false;
    }

    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'slug' => ['required', 'string', 'max:255', Rule::unique('ad_packages', 'slug')->ignore($this->route('ad_package'))],
            'duration_days' => ['required', 'integer', 'min:1'],
            'price' => ['required', 'numeric', 'min:0'],
            'placement_type' => ['required', Rule::in(array_keys(AdPackage::placementOptions()))],
            'status' => ['required', Rule::in(['active', 'inactive'])],
        ];
    }
}
