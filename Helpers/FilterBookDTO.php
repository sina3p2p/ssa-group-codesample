<?php

namespace App\Domains\Book\Helpers;

use App\Domains\Book\Models\BookVariation;
use App\Helpers\DTO;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class FilterBookDTO extends DTO
{

    public ?int $category_id;
    public ?array $f_category_id;
    public ?array $serie_id;
    public ?array $author_id;
    public ?float $price_from;
    public ?float $price_to;
    public ?string $type;
    public ?int $year;
    public ?array $price_range;
    public bool $best;
    public bool $author;
    public bool $category;
    public bool $serie;
    public ?int $block;
    public string $order;
    public string $sort;
    public ?string $isbn;
    public int $per_page;
    public ?string $q;
    public ?array $except;
    public bool $discount;

    public function setValues(Request $request)
    {
        $this->category_id   = $request->input('category_id');
        $this->f_category_id = $request->f_category_id  ? explode(',', $request->f_category_id ) : null;
        $this->serie_id      = $request->serie_id       ? explode(',',      $request->serie_id ) : null;
        $this->author_id     = $request->author_id      ? explode(',',     $request->author_id ) : null;
        $this->price_from    = $request->input('price_from');
        $this->price_to      = $request->input('price_to');
        $this->year          = $request->input('year');
        $this->price_range   = ($this->price_to) ? [$this->price_from ?? 0, $this->price_to] : null;
        $this->best          = $request->input('best') == 1 ? true : false;
        $this->discount      = $request->input('discount') == 1 ? true : false;
        $this->author        = $request->input('author') == 1 ? true : false;
        $this->serie         = $request->input('serie') == 1 ? true : false;
        $this->category      = $request->input('category') == 1 ? true : false;
        $this->block         = $request->input('block');
        $this->order         = $request->get('order', 'updated_at');
        $this->sort          = $request->get('sort', 'desc');
        $this->isbn          = $request->isbn;
        $this->per_page      = $request->get('per_page', 24);
        $this->q             = $request->input('q');
        $this->except        = $request->except      ? explode(',',     $request->except ) : null;
        $dynamic       = [];
        foreach($request->except(array_keys((get_class_vars(__CLASS__)))) as $key => $val)
        {
            if(Str::startsWith($key, 'field'))
            {
                $dynamic[$key] = $val; 
            }
        }
        $this->dynamic = $dynamic;
    }
}
