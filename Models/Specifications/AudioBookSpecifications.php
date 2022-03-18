<?php

namespace App\Domains\Book\Models\Specifications;

class AudioBookSpecifications extends Specification
{

    public ?string $voiceOver;
    public ?string $file_path;

    public static function rules(): array
    {
        return [
            "file_path" => "required_without:id|required_if:id,0|exists:file_uploads,id",
            "voiceOver" => "required|string",
        ];
    }

    public static function files() : array
    {
        return ['file_path'];
    }

}
