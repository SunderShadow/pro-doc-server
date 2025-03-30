<?php

namespace App\Service;

class Base64ImageDecoder
{
    public readonly string $extension;

    public readonly string $mime;

    public readonly string $data;

    public function __construct(string $base64data)
    {
        $this->mime = mime_content_type($base64data);
        if (str_starts_with($base64data, 'data:image')) {
            $parts = explode(',', $base64data);
            $base64data = $parts[1];
        }

        $this->data = base64_decode($base64data);
        $this->extension = explode('/', $this->mime)[1];
    }

    public static function decode(string $base64data): self
    {
        return new self($base64data);
    }
}