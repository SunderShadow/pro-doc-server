<?php

namespace App\Service;

class Base64ImageDecoder
{
    public readonly string $extension;

    public readonly string $mime;

    public readonly string $data;

    public function __construct(string $base64data)
    {
        $f = new \finfo();
        $this->data = base64_decode($base64data);
        $this->mime = $f->buffer($this->data, FILEINFO_MIME_TYPE);
        $this->extension = explode('/', $this->mime)[1];
    }

    public static function decode(string $base64data): self
    {
        return new self($base64data);
    }
}