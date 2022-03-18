<?php

namespace App\Domains\Book\Services;

use App\Domains\Book\Helpers\AuthorDTO;
use App\Domains\Book\Helpers\ValidatedAuthorDTO;
use App\Domains\Book\Repositories\AuthorRepository;

class AuthorService {

    protected AuthorRepository $repository;

    public function __construct(AuthorRepository $repository)
    {
        $this->repository = $repository;
    }

    public function index(AuthorDTO $dto)
    {
        return $this->repository->getAll($dto);
    }
  
    public function store(ValidatedAuthorDTO $dto)
    {
        return $this->repository->store($dto);
    }
    
}
