<?php

namespace App\Domains\Book\Helpers;

use App\Domains\Book\Models\BookVariation;
use App\Helpers\DTO;
use Illuminate\Http\Request;

class ShowBookDTO extends DTO
{

    public int $id;
    public bool $author;
    public bool $category;
    public bool $series;
    public int  $limit;

    public function setValues(Request $request)
    {
        $this->id            = $request->id ?? 0;
        $this->author        = !!$request->get('author') ?: false;
        $this->category      = !!$request->get('category') ?: false;
        $this->series        = !!$request->get('series') ?: false;
        $this->limit         = $request->get('limit', 10);
    }
}