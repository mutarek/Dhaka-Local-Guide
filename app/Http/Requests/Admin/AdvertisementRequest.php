<?php

namespace App\Http\Requests\Admin;

use App\Models\AdPackage;
use App\Models\Advertisement;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class AdvertisementRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()?->can('viewAny', Advertisement::class) ?? false;
    }

    public function rules(): array
    {
        return [
            'advertiser_id' => ['required', 'exists:advertisers,id'],
            'ad_package_id' => ['required', 'exists:ad_packages,id'],
            'title' => ['required', 'string', 'max:255'],
            'image' => ['required', 'image', 'mimes:jpg,jpeg,png,webp', 'max:2048'],
            'mobile_image' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:1536'],
            'destination_url' => ['required', 'url:http,https', 'max:2048'],
            'placement_position' => ['required', Rule::in(array_keys(AdPackage::placementOptions()))],
            'target_type' => ['required', Rule::in(array_keys(Advertisement::targetOptions()))],
            'category_id' => ['nullable', 'required_if:target_type,category', 'exists:categories,id'],
            'start_date' => ['required', 'date'],
            'end_date' => ['required', 'date', 'after:start_date'],
            'status' => ['required', Rule::in(['draft', 'active', 'expired', 'paused'])],
            'amount_paid' => ['required', 'numeric', 'min:0'],
            'payment_status' => ['required', Rule::in(['unpaid', 'partial', 'paid'])],
            'priority' => ['required', 'integer', 'min:0'],
        ];
    }
}
