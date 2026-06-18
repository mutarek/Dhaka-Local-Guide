<?php

namespace App\Support;

class SeoData
{
    public function __construct(
        public string $title,
        public string $description,
        public ?string $canonical = null,
        public ?string $image = null,
        public string $type = 'website',
        public array $schema = [],
    ) {}
}
