<?php

namespace App\Domains\Book\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AuthorTranslation extends Model
{
    use HasFactory;

    protected $guarded = [
        'id'
    ];

    public $timestamps = false;

}
