<?php

namespace App\Domains\Book\Models\Specifications;

class EBookSpecifications extends Specification
{

    public ?string $file_path;

    public ?string $demo_file_path;

    public static function rules(): array
    {
        return [
            "file_path" => "required_without:id|required_if:id,0|exists:file_uploads,id",
            "demo_file_path" => "required_without:id|required_if:id,0|exists:file_uploads,id",
        ];
    }

    public static function files() : array
    {
        return ['file_path', 'demo_file_path'];
    }

}
