<?php

namespace App\Domains\Book\Helpers;

use App\Domains\Book\Models\Serie;
use App\Helpers\DTO;
use App\Helpers\TranslatableDTO;
use Illuminate\Http\Request;

class ValidatedSerieDTO extends TranslatableDTO
{

    public int $id;
    // public ?string $title;
    public ?string $image;

    public function setValues(Request $request)
    {
        $this->id            = $request->get('id', 0);
        // $this->title         = $request->input('title');
        $this->fillTranslates($request);
        $this->image         = $this->upload('image', optional(Serie::find($this->id))->image, 'serie', false);
    }

    protected function rules() : array
    {
        return [
            "id"        => "exclude_if:id,0|sometimes|exists:series",
            "ka.title"  => 'required|string',
            "image"     => 'sometimes|nullable|file'
        ];
    }

}
