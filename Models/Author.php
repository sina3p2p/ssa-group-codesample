<?php

namespace App\Domains\Book\Models;

use App\Helpers\EloquentImageMutatorTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Astrotomic\Translatable\Translatable;

class Author extends Model
{
    use HasFactory, EloquentImageMutatorTrait, Translatable;

    protected $image_fields = ['img'];

    public $guarded = ['id'];

    public $translatedAttributes = ['fullname', 'description', 'country'];

    protected $casts = [
        'best_of_week_books' => 'array'
    ];

    public function books()
    {
        return $this->hasMany(Book::class);
    }

}
