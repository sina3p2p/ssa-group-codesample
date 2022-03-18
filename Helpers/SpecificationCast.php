<?php

namespace App\Domains\Book\Helpers;

use App\Domains\Book\Models\BookVariation;
use App\Domains\Book\Models\Specification;
use Illuminate\Contracts\Database\Eloquent\CastsAttributes;
use InvalidArgumentException;

class SpecificationCast implements CastsAttributes
{

    public function get($model, $key, $value, $attributes)
    {
        if (!isset($attributes['variation']) || !key_exists($attributes['variation'], BookVariation::Variations)) {
            throw new InvalidArgumentException('The given model does not contain a valid variation.');
        }

        $class = BookVariation::Variations[$attributes['variation']];

        return new $class(json_decode($value ?? "[]", true));
    }


    public function set($model, $key, $value, $attributes)
    {
        return (is_array($value) || is_object($value)) ? [$key => json_encode($value)] : [$key => $value];
    }

}
