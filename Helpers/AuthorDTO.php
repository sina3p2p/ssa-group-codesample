<?php

namespace App\Domains\Book\Helpers;

use App\Domains\Book\Models\BookVariation;
use App\Helpers\DTO;
use Illuminate\Http\Request;

class AuthorDTO extends DTO
{

    public bool $book;
    public int $book_limit;
    public ?string $q;
    public int $per_page;
    public bool $random;

    public function setValues(Request $request)
    {
        $this->book          = $request->has('book');
        $this->book_limit    = $request->get('book_limit', 2);
        $this->q             = $request->input('q');
        $this->per_page      = $request->get('per_page', 16);
        $this->random        = $request->has('random');
    }
}
