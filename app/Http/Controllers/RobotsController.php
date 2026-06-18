<?php

namespace App\Http\Controllers;

use Illuminate\Http\Response;

class RobotsController extends Controller
{
    public function __invoke(): Response
    {
        $content = implode("\n", [
            'User-agent: *',
            'Disallow:',
            'Sitemap: '.route('sitemap'),
            '',
        ]);

        return response($content, 200)->header('Content-Type', 'text/plain');
    }
}
