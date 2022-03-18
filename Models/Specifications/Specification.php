<?php

namespace App\Domains\Book\Models\Specifications;

use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Support\Facades\Storage;

abstract class Specification implements Arrayable
{
    public function __construct(array $parameters = [])
    {
        $class = new \ReflectionClass(static::class);

        foreach ($class->getProperties(\ReflectionProperty::IS_PUBLIC) as $reflectionProperty){
            $property = $reflectionProperty->getName();
            $this->{$property} = $parameters[$property] ?? null;
        }
    }

    public static function rules() : array
    {
        return [];
    }

    public static function files() : array
    {
        return [];
    }

    public function toArray()
    {
        $arr = (array) $this;

        foreach(static::files() ?? [] as $key)
        {
            if(isset($arr[$key]))
            {
                $arr[$key] = url(Storage::url($arr[$key]));
            }
        }

        return $arr;
    }

}
