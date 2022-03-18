<?php

namespace App\Domains\Book\Helpers;

use App\Helpers\DTO;
use Illuminate\Http\Request;

class VariationStocksDTO extends DTO
{
    public array $vendorIds;

    public function setValues(Request $request)
    {
        $this->vendorIds = $request->vendorIds;
    }

    protected function rules(): array
    {
        return [
            "vendorIds" => "required|array|min:1"
        ];
    }
}