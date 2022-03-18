<?php

namespace App\Domains\Book\Helpers;

use App\Domains\Book\Models\Book;
use App\Domains\Book\Models\BookDetail;
use Illuminate\Http\Request;
use App\Helpers\DTO;
use App\Helpers\TranslatableDTO;
use Carbon\Carbon;

class ValidatedBookDTO extends TranslatableDTO
{

    protected $model = Book::class;

    public int $id = 0;
    public int $author_id = 0;
    public int $serie_id = 0;
    public string $name;
    public string $description;
    public ?string $isbn;
    // public array  $variations;
    public ?array $pictures = [];
    public bool $is_bestseller = false;
    public bool $is_palitra = false;
    public int $category_id = 0;
    public int $year = 0;
    public int $year_range = 0;
    public ?string $publisher;
    public ?string $translator;
    public ?string $language;
    public BookDetail $details;
    public int $block = 0;
    public int $position = 0;
    public ?string $cover;
    public ?Carbon $created_at;

    public function setValues(Request $request)
    {
        $this->id            = $request->get('id', 0);
        $this->author_id     = $request->get('author_id', 0);
        $this->serie_id      = $request->get('serie_id', 0);
        $this->block         = $request->get('block', 0);
        $this->isbn          = $request->input('isbn');
        // $this->variations    = $request->input('variations');
        $this->pictures      = $this->upload('pictures', 'book');
        $this->cover         = $this->upload('cover', 'book');
        $this->position      = $request->get('position', 0);
        // $this->files         = $request->file('files' , []);
        $this->is_bestseller = $request->input('is_bestseller' , false);
        $this->is_palitra    = $request->input('is_palitra' , false);
        $this->category_id   = $request->input('category_id' , 0);
        $this->year          = $request->input('year') ?? 0;
        $this->year_range    = $request->input('year_range' , 0);
        $this->publisher     = $request->input('publisher');
        $this->translator    = $request->input('translator');
        $this->language      = $request->input('language');
        $this->details       = BookDetail::fromArray($request->input('details', []));
        $this->created_at    = $request->created_at ? Carbon::parse($request->created_at) : null;
        $this->fillTranslates($request);
    }

    protected function rules() : array
    {
        return [
            "id"                          => "exclude_if:id,0|sometimes|exists:books",
    		"ka.name"                     => "required|string",
            "isbn"                        => "sometimes|string|unique:books,isbn,".$this->request->get('id', 0),
            // "variations"                  => [ "required", new VariationsValidator],
            "category_id"                 => "sometimes|numeric|exists:categories,id",
            "year"                        => "sometimes|numeric|nullable",
            "is_bestseller"               => "sometimes|in:0,1",
            "details"                     => "array",
            "author_id"                   => "exclude_if:author_id,0|sometimes|exists:authors,id",
            "serie"                       => "exclude_if:serie_id,0|sometimes|exists:series,id",
            "pictures"                    => "required_without:id|array",
            "pictures.*"                  => "required_without:id|file",
            "block"                       => "sometimes|in:0,".implode(',',Book::BLOCK),
            "year_range"                  => "sometimes|integer",
        ];
    }

    public function upload2($imgKey, $path)
    {
        $file = $this->request->file($imgKey);

        if ($file) {
            if(is_array($file))
            {
                $uploadedImg = array();
                foreach($file as $f)
                {
                    $uploadedImg[] = $f->store('public/' . $path);
                }
            }
            
        }

        return array_merge( $uploadedImg ?? [], $this->request->{$imgKey.'_paths'});
    }

    public function toArray() : array
    {
        $array =  (array) $this;

        $array['details'] = $this->details->toArray();

        return $array;
    }

}
