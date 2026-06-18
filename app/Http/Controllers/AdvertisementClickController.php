<?php

namespace App\Http\Controllers;

use App\Models\Advertisement;
use Illuminate\Http\RedirectResponse;

class AdvertisementClickController extends Controller
{
    public function __invoke(Advertisement $advertisement): RedirectResponse
    {
        abort_unless(Advertisement::hasSafeDestinationUrl($advertisement->destination_url), 404);

        $advertisement->increment('clicks_count');

        return redirect()->away($advertisement->destination_url);
    }
}
