<?php

namespace App\Domains\Book\Models;

use App\Domains\Category\Models\Category;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Discount extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    public function categories()
    {
        return $this->morphedByMany(Category::class, 'discountable');
    }

    public function series()
    {
        return $this->morphedByMany(Serie::class, 'discountable');
    }

    public function books()
    {
        return $this->morphedByMany(Book::class, 'discountable');
    }
}
