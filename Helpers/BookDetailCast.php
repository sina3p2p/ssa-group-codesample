<?php

namespace App\Domains\Book\Helpers;

use App\Domains\Book\Models\BookDetail;
use Illuminate\Contracts\Database\Eloquent\CastsAttributes;
use InvalidArgumentException;

class BookDetailCast implements CastsAttributes
{

    public function get($model, $key, $value, $attributes)
    {
        return BookDetail::fromArray(json_decode($value ?? '[]', true));
    }


    public function set($model, $key, $value, $attributes)
    {
        return is_array($value) ? [$key => json_encode($value)] : [$key => $value];
    }

}
