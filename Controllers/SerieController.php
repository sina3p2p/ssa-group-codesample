<?php

namespace App\Domains\Book\Controllers;

use App\Domains\Book\Helpers\ValidatedSerieDTO;
use App\Domains\Book\Services\SerieService;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class SerieController extends Controller
{

    protected SerieService $service;

    public function __construct(SerieService $service)
    {
        $this->service = $service;
    }

    public function index(Request $request)
    {
        return $this->service->index($request->q);
    }

    public function store(Request $request)
    {
        return $this->service->store(ValidatedSerieDTO::fromRequest($request));
    }

}
