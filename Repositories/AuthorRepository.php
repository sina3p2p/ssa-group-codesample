<?php

namespace App\Domains\Book\Repositories;

use App\Domains\Book\Helpers\AuthorDTO;
use App\Domains\Book\Models\Author;
use App\Domains\Book\Helpers\ValidatedAuthorDTO;
use Illuminate\Support\Facades\Auth;

class AuthorRepository
{
    protected Author $model;

    public function __construct(Author $model)
    {
        $this->model = $model;
    }

    public function getAll(AuthorDTO $dto)
    {
        return
            $this->model
            ->when($dto->q,      function($q2) use ($dto) {
                $q2->whereTranslationLike('fullname', '%'.$dto->q.'%');
            })
            ->when($dto->book,      function($q2) use ($dto) {
                $q2->with(['books' => function($query) use ($dto){
                    $query->limit($dto->book_limit)->with(['wishes' => function ($q)
                    {
                        $q->where('wish_lists.user_id', Auth::guard('api')->id() ?? 0)->where('type', 0);
                    }])
                    ->with('category')
                    ->when($dto->random, function($q3) {
                        $q3->inRandomOrder();
                    });
                }]);
            })
            ->when($dto->random, function($q2) {
                $q2->inRandomOrder();
            })
            ->latest()
            ->paginate($dto->per_page);
    }

    public function store(ValidatedAuthorDTO $dto)
    {
	    return $this->model->updateOrCreate(
            ['id' => $dto->id],
            $dto->toArray()
        );
    }

}
