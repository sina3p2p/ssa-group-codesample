<?php

namespace App\Domains\Book\Controllers;

use App\Domains\Book\Helpers\AuthorDTO;
use App\Domains\Book\Helpers\ValidatedAuthorDTO;
use App\Domains\Book\Services\AuthorService;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class AuthorController extends Controller
{

    protected AuthorService $service;

    public function __construct(AuthorService $service)
    {
        $this->service = $service;
    }

    public function index(Request $request)
    {
        return $this->service->index(AuthorDTO::fromRequest($request));
    }

    public function store(Request $request)
    {
        return $this->service->store(ValidatedAuthorDTO::fromRequest($request));
    }

}
