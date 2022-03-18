<?php

namespace App\Domains\Book\Models\Specifications;

class PaperBookSpecifications extends Specification
{

    public ?string $format;

    public ?string $cover;

    public ?int $numPages;

    public ?float $weight;
}
