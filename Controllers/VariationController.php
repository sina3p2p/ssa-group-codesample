<?php

namespace App\Domains\Book\Controllers;

use App\Domains\Book\Helpers\ValidatedVariationDTO;
use App\Domains\Book\Helpers\VariationStocksDTO;
use App\Domains\Book\Services\VariationService;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class VariationController extends Controller
{

    protected VariationService $service;

    public function __construct(VariationService $service)
    {
        $this->service = $service;
    }

    public function index()
    {
        return $this->service->index();
    }

    public function store(Request $request)
    {
        return $this->service->store(ValidatedVariationDTO::fromRequest($request));
    }
    
}
