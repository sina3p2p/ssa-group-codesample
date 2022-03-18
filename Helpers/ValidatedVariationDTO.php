<?php

namespace App\Domains\Book\Helpers;

use App\Domains\Book\Models\Author;
use App\Domains\Book\Models\BookVariation;
use App\Domains\Book\Models\Specification;
use App\Domains\Category\Models\Category;
use App\Domains\FileUpload\Models\FileUpload;
use App\Helpers\DTO;
use Illuminate\Http\Request;
use Carbon\Carbon;

class ValidatedVariationDTO extends DTO
{

    public int $id;
    public string $variation;
    public int $book_id;
    // public float $price;
    public float $discount;
    public ?Carbon $start_discount;
    public ?Carbon $end_discount;
    public bool $instock;
    // public string $vendor_id;
    public ?array $specifications;
    public int $last_update;

    public function setValues(Request $request)
    {
        $this->id             = $request->get('id', 0);
        $this->variation      = $request->get('variation', 'defualt');
        $this->book_id        = $request->input('book_id');
        // $this->price          = $request->input('price', 0);
        // $this->discount       = $request->get('discount', 0);
        $this->instock        = $request->get('instock', false);
        $this->vendor_id      = $request->get("vendor_id");
        $this->start_discount = $request->start_discount ? Carbon::parse($request->start_discount) : null;
        $this->end_discount   = $request->end_discount ? Carbon::parse($request->end_discount) : null;
        $this->specifications = $request->specs;
        $this->last_update    = time();
        // $specifications = optional(BookVariation::find($this->id))->specifications ?? app(BookVariation::Variations[$this->variation]);
        // $file_fields = $specifications->files();
        // foreach($request->except('id', "variation", "book_id", "price", "instock") as $key => $value)
        // {
        //     if(in_array($key, $file_fields)){
        //         $specifications->$key = optional(FileUpload::find($value))->path;
        //     }else{
        //         $specifications->$key = $value;
        //     }
        // }
        // $this->specifications = (array) $specifications;
    }

    protected function rules() : array
    {

        $specifications_rule = [];
        $category = Category::find($this->request->category_id);
        if($category)
        {
            foreach($category->specifications as $spec)
            {
                if($spec->is_required)
                {
                    $specifications_rule['specs.*.'.$spec->field][] = "required";
                }

                if($spec->element_type == "file")
                {
                    $specifications_rule['specs.*.'.$spec->field][] = "required_without:id|required_if:id,0|exists:file_uploads,id";
                }
            }
        }


        return array_merge([
            "id"                 => "exclude_if:id,0|sometimes|exists:book_variations",
            // "variation"     => "required",
            "book_id"            => "required|exists:books,id",
            "category_id"        => "required|exists:categories,id",
            "specs.*.price"      => "required",
            "instock"            => "sometimes|boolean",
            "discount"           => "max:100",
            "specs.*.vendor_id"  => "required|string",
            // "specs.*.field"      => "required|exists:specification_els,field",
        ], $specifications_rule);
    }

    // public function validate(Request $request)
    // {

    //     $rules = $this->rules();

    //     $variations_type = BookVariation::Variations;

    //     $specificationsClass = data_get($variations_type, $request->variation ?? '', null);

    //     if($specificationsClass)
    //     {
    //         $rules = array_merge($rules, $specificationsClass::rules());
    //     }

    //     $validated = $request->validate($rules);

    //     return $validated;

    // }


}
