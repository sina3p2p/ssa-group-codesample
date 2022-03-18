<?php

namespace App\Domains\Book\Services;

use App\Domains\Book\Helpers\FilterBookDTO;
use App\Domains\Book\Helpers\ShowBookDTO;
use App\Domains\Book\Helpers\ValidatedBookDTO;
use App\Domains\Book\Repositories\AuthorRepository;
use App\Domains\Book\Repositories\BookRepository;
use App\Domains\Book\Resources\SearchResource;

class BookService {

    protected BookRepository $repository;

    public function __construct(BookRepository $repository)
    {
        $this->repository = $repository;
    }

    public function index(FilterBookDTO $dto)
    {
        return $this->repository->showBookList($dto);
    }
    
    public function store(ValidatedBookDTO $dto)
    {
        return $this->repository->saveBook($dto);
    }

}
