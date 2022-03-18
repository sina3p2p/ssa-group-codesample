<?php

namespace App\Domains\Book\Services;

use App\Domains\Book\Helpers\ValidatedVariationDTO;
use App\Domains\Book\Repositories\VariationRepository;

class VariationService
{

    protected VariationRepository $repository;

    public function __construct(VariationRepository $repository) {
        $this->repository = $repository;
    }

    public function index()
    {
        return $this->repository->getAll();
    }

    public function store(ValidatedVariationDTO $dto)
    {
        return $this->repository->store($dto)->book->load('variations');
    }

    public function destroy(int $id)
    {
        $this->repository->deleteByID($id);
    }
}
