<?php

namespace App\Domains\Book\Services;

use App\Domains\Book\Helpers\ValidatedSerieDTO;
use App\Domains\Book\Repositories\SerieRepository;

class SerieService {

    protected SerieRepository $repository;

    public function __construct(SerieRepository $repository)
    {
        $this->repository = $repository;
    }

    public function index($q = null)
    {
        return $this->repository->getAll($q);
    }

    public function store(ValidatedSerieDTO $dto)
    {
        return $this->repository->store($dto);
    }

    public function destroy(int $id)
    {
        $this->repository->deleteByID($id);
    }

    public function show($id)
    {
        return $this->repository->findByID($id);
    }

}
