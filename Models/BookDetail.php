<?php

namespace App\Domains\Book\Models;

use Illuminate\Contracts\Support\Arrayable;

class BookDetail implements Arrayable
{

    public ?string $desc;

    public ?string $youtube_link;

    public ?string $soundcloud_link;

    public ?string $book_link;

    public static function fromArray(array $args) : self
    {
        $obj = new self();
        $class = new \ReflectionClass($obj);
        foreach ($class->getProperties(\ReflectionProperty::IS_PUBLIC) as $reflectionProperty){
            $property = $reflectionProperty->getName();
            $obj->{$property} = $args[$property] ?? null;
        }
        return $obj;
    }

    public function toArray() : array
    {
        return (array) $this;
    }

}
