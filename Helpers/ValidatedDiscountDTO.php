<?php

namespace App\Domains\Book\Helpers;

use App\Domains\Book\Models\Author;
use App\Domains\Book\Models\Book;
use App\Helpers\DTO;
use App\Helpers\TranslatableDTO;
use Illuminate\Http\Request;
use Carbon\Carbon;

class ValidatedDiscountDTO extends DTO
{
    public int     $id;
    public array   $category_id;
    public array   $serie_id;
    public array   $isbn;
    public ?string  $discount_name;
    public float   $discount;
    public bool    $apply_to_all;
    public ?Carbon $start_date;
    public ?Carbon $end_date;
    public bool    $only_website;

    public function setValues(Request $request)
    {
        $this->id            = $request->get('id', 0);
        $this->category_id   = $request->input('category_id', []);
        $this->serie_id      = $request->input('serie_id', []);
        $this->isbn          = $request->input('isbn', []);
        $this->discount      = $request->discount;
        $this->apply_to_all     = $request->input('apply_to_all', false);
        $this->start_date    = $request->start_date ? Carbon::parse($request->start_date) : null;
        $this->end_date      = $request->end_date ? Carbon::parse($request->end_date) : null;
        $this->only_website  = $request->input('only_website', false);
        $this->discount_name = $request->input('discount_name');
    }

    protected function rules() : array
    {
        return [
            "id"        => "exclude_if:id,0",
            "isbn"      => 'array',
            "isbn.*" => 'required|string',
            "category_id"  => 'array',
            "serie_id"  => 'array',
            "discount"  => 'required|numeric|max:100',
            "apply_to_all"   => "boolean",
            "only_website"  => 'boolean',
        ];
    }
}