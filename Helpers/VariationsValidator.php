<?php

namespace App\Domains\Book\Helpers;

use App\Domains\Book\Models\BookVariation;
use Illuminate\Contracts\Validation\Rule;

class VariationsValidator implements Rule
{
    /**
     * Determine if the validation rule passes.
     *
     * @param  string  $attribute
     * @param  mixed  $value
     * @return bool
     */
    public function passes($attribute, $value)
    {
        if(!is_array($value))
            return false;

        $variations_type = BookVariation::Variations;

        foreach($value as $variation){

            if(!in_array(data_get($variation, 'variation', null), array_keys($variations_type)))
                return false;

            if(!isset($variation['price']))
                return false;

            $class = new \ReflectionClass($variations_type[$variation['variation']]);

            foreach ($class->getProperties(\ReflectionProperty::IS_PUBLIC) as $reflectionProperty){
                $property = $reflectionProperty->getName();
                if(
                    !$reflectionProperty->getType()->allowsNull() &&
                    !data_get($variation, 'specifications.'.$property)
                ) {
                    return false;
                }
            }

        }

        return true;
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return 'check :attribute inputs and requireds.';
    }
}
