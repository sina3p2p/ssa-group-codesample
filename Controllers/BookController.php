<?php

namespace App\Domains\Book\Controllers;

use App\Domains\Book\Exports\BookExport;
use App\Domains\Book\Helpers\FilterBookDTO;
use App\Domains\Book\Helpers\ShowBookDTO;
use App\Domains\Book\Helpers\ValidatedBookDTO;
use App\Domains\Book\Models\Book;
use App\Domains\Book\Models\Discount;
use App\Domains\Book\Services\BookService;
use App\Domains\Category\Models\Category;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

class BookController extends Controller
{

    protected BookService $service;

    public function __construct(BookService $service)
    {
        $this->service = $service;
    }

    public function index(Request $request)
    {
        return $this->service->index(FilterBookDTO::fromRequest($request));
    }

    public function store(Request $request)
    {
        return $this->service->store(ValidatedBookDTO::fromRequest($request));
    }
    
}
