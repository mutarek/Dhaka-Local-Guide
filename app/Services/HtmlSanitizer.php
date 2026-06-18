<?php

namespace App\Services;

use DOMDocument;
use DOMElement;
use DOMNode;
use DOMXPath;

class HtmlSanitizer
{
    private const ALLOWED_TAGS = [
        'p', 'br', 'strong', 'b', 'em', 'i', 'u', 'ul', 'ol', 'li',
        'blockquote', 'a', 'h2', 'h3', 'h4', 'img', 'figure', 'figcaption',
        'table', 'thead', 'tbody', 'tr', 'th', 'td',
    ];

    private const ALLOWED_ATTRIBUTES = [
        'a' => ['href', 'title', 'target', 'rel'],
        'img' => ['src', 'alt', 'title', 'width', 'height'],
        '*' => ['class'],
    ];

    public function sanitize(?string $html): ?string
    {
        if ($html === null || trim($html) === '') {
            return $html;
        }

        $document = new DOMDocument;
        libxml_use_internal_errors(true);
        $document->loadHTML('<?xml encoding="utf-8" ?><div>'.$html.'</div>', LIBXML_HTML_NOIMPLIED | LIBXML_HTML_NODEFDTD);
        libxml_clear_errors();

        $xpath = new DOMXPath($document);

        foreach ($xpath->query('//*') as $node) {
            if (! $node instanceof DOMElement) {
                continue;
            }

            if (! in_array($node->nodeName, self::ALLOWED_TAGS, true) && $node->nodeName !== 'div') {
                $this->unwrap($node);
                continue;
            }

            $this->sanitizeAttributes($node);
        }

        $wrapper = $document->getElementsByTagName('div')->item(0);

        if (! $wrapper) {
            return strip_tags($html, '<'.implode('><', self::ALLOWED_TAGS).'>');
        }

        return trim(collect(iterator_to_array($wrapper->childNodes))
            ->map(fn (DOMNode $node): string => $document->saveHTML($node))
            ->implode(''));
    }

    private function sanitizeAttributes(DOMElement $node): void
    {
        $allowed = array_merge(
            self::ALLOWED_ATTRIBUTES['*'] ?? [],
            self::ALLOWED_ATTRIBUTES[$node->nodeName] ?? [],
        );

        foreach (iterator_to_array($node->attributes) as $attribute) {
            $name = strtolower($attribute->nodeName);
            $value = trim($attribute->nodeValue);

            if (str_starts_with($name, 'on') || ! in_array($name, $allowed, true)) {
                $node->removeAttribute($attribute->nodeName);
                continue;
            }

            if (in_array($name, ['href', 'src'], true) && preg_match('/^\s*(javascript|data):/i', $value)) {
                $node->removeAttribute($attribute->nodeName);
            }
        }

        if ($node->nodeName === 'a') {
            $rel = collect(explode(' ', $node->getAttribute('rel')))
                ->merge(['noopener'])
                ->filter()
                ->unique()
                ->implode(' ');

            $node->setAttribute('rel', $rel);
        }
    }

    private function unwrap(DOMNode $node): void
    {
        while ($node->firstChild) {
            $node->parentNode?->insertBefore($node->firstChild, $node);
        }

        $node->parentNode?->removeChild($node);
    }
}
