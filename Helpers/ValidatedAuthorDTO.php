<?php

namespace App\Domains\Book\Helpers;

use App\Domains\Book\Models\Author;
use App\Helpers\DTO;
use App\Helpers\TranslatableDTO;
use Illuminate\Http\Request;
use Carbon\Carbon;

class ValidatedAuthorDTO extends TranslatableDTO
{

    protected $model = Author::class;

    public int $id;
    // public ?string $fullname;
    public ?string $img;
    // public ?string $description;
    // public ?string $country;
    public bool $best_of_week = false;
    public ?array $best_of_week_books;
    public ?Carbon $year_start;
    public ?Carbon $year_end;

    public function setValues(Request $request)
    {
        $this->id                 = $request->get('id', 0);
        $this->img                = $this->upload("img", "author");
        $this->best_of_week       = $request->get('best_of_week', 0);
        $this->best_of_week_books = $request->input('best_of_week_books');
        $this->year_start         = $request->year_start ? Carbon::parse($request->year_start) : null;
        $this->year_end           = $request->year_end ? Carbon::parse($request->year_end) : null;
        $this->fillTranslates($request);
    }

    protected function rules() : array
    {
        return [
            "id"          => "exclude_if:id,0|sometimes|exists:authors",
            "ka.fullname" => "required|string",
        ];
    }
}
