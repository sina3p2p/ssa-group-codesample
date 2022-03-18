<?php

namespace App\Domains\Book\Repositories;

use App\Domains\Book\Helpers\FilterBookDTO;
use App\Domains\Book\Helpers\ShowBookDTO;
use App\Domains\Book\Helpers\ValidatedBookDTO;
use App\Domains\Book\Models\Book;
use App\Domains\Category\Models\Category;
use Illuminate\Support\Facades\Auth;

class BookRepository
{
    protected Book $book;

    public function __construct(Book $book)
    {
        $this->book = $book;
    }

    protected function getCommonQuery(FilterBookDTO $dto = null, string $q = null)
    {
        $book = new Book();
        $book->disableAutoloadTranslations();

        $book = $q->whereIn('books.id', function($query) use ($dto) {
                    $query
                    ->from('book_variations')
                    ->selectRaw('book_variations.book_id')
                    ->where('book_variations.stock_count', '>', config('vendors.1c.minStock'))
                    ->when($dto->discount, function ($q)
                    {
                        $q->where('book_variations.discount', '>', 0)->whereDate('start_discount', '<=', now())->whereDate('end_discount', '>=', now());
                    })
                    ->when($dto && $dto->price_range, function($q) use ($dto){
                        $q->whereBetween('book_variations.price', $dto->price_range);
                    })
                    // ->when($dto->type, fn($q) => $query->where('variation', $dto->type));
                    ->when($dto && $dto->dynamic && count($dto->dynamic), function ($q2) use ($dto)
                    {
                        $q2->whereIn('book_variations.id', function($query) use ($dto) {

                            $query->from('specifications')->select('specifications.variation_id');

                            foreach($dto->dynamic as $key => $value){
                                $query->where('specifications.field', $key)->whereIn('specifications.value', explode(',', urldecode($value)));
                            }
                        });
                    });
                });

        return

            $book->when($q, function($q2) use ($q) {
                $q2->where(function ($q3) use ($q)
                {
                    $keywords = explode(' ', $q);
                    $q3->where('book_translations.name', 'like', '%'.$q.'%');
                    foreach ($keywords as $i=>$k){
                        $q3->orWhere('name', 'like', '%'.$k.'%');
                    }
                    $q3->orWhereHas('author', function ($authorQ) use ($keywords)
                    {
                        foreach ($keywords as $i => $k){
                            if($i == 0){
                                $authorQ->whereTranslationLike('fullname', '%'.$k.'%');
                            }else{
                                $authorQ->orWhereTranslationLike('fullname', 'Like', '%'.$k.'%');
                            }
    
                        }
                    })->orWhere('isbn', 'Like', '%'.$q.'%');
                });

                $q2
                ->orderByRaw("
                CASE WHEN book_translations.name = '".$q."' THEN 0
                WHEN book_translations.name LIKE '".$q."%' THEN 1
                WHEN book_translations.name LIKE '%".$q."%' THEN 2
                WHEN book_translations.name LIKE '%".$q."' THEN 3
                ELSE 4 END");
            })
            ->when($dto->category_id, function($q) use ($dto) {
                $cat = Category::find($dto->category_id);
                if($cat){
                    $q->whereIn('books.category_id', function($query) use ($cat) {

                        $query->from('categories')->select('categories.id')
                            ->whereBetween('categories._lft', [(int) $cat->_lft, (int) $cat->_rgt]);
                    });
                }
            })
            ->when($dto->f_category_id, function($q) use ($dto) {
                $q->whereIn('category_id', $dto->f_category_id);
            })
            ->when($dto->author_id , function($q) use ($dto) {
                $q->whereIn('author_id', $dto->author_id);
            })
            ->when($dto->serie_id, function($q) use ($dto) {
                $q->whereIn('serie_id', $dto->serie_id);
            })
            ->when($dto->except, function($q) use ($dto) {
                $q->whereNotIn('books.id', $dto->except);
            })
            ->when($dto->year,     fn($q) => $q->where('year_range', $dto->year))
            ->when($dto->block,    fn($q) => $q->where('block', $dto->block))
            ->when($dto->best,     fn($q) => $q->where('is_bestseller', true))
            ->when($dto->author,   fn($q) => $q->with('author'))
            ->when($dto->category, fn($q) => $q->with('category'))
            ->when($dto->isbn,     fn($q) => $q->where('isbn', 'LIKE', '%'.$dto->isbn.'%'))
            ->withTransalationsJoin();

    }


    public function showBookList(FilterBookDTO $dto)
    {
        
        $query =  $this->getCommonQuery($dto, $dto->q)
        ->select('books.id', 'books.url', 'books.category_id', 'books.position', 'books.cover', 'books.pictures', 'books.author_id', 'books.is_bestseller', 'book_translations.name')
        ->withRate()
        ->withVariationStatus()
        ->withVariation();

        if($dto->order == "price")
        {
            $query = $query->withPrice()->orderBy('p', $dto->sort);
        }else {
            $query = $query->orderBy('books.'.$dto->order, $dto->sort);
        }

        return $query->with(['wishes' => function ($q)
        {
            $q->where('wish_lists.user_id', Auth::guard('api')->id() ?? 0);
        }])
        ->paginate($dto->per_page);
    }

    public function saveBook(ValidatedBookDTO $bookDTO) : Book
    {
        $book = $this->book->updateOrCreate(
            ['id' => $bookDTO->id],
            array_filter($bookDTO->toArray())
        );
        return $book->load('variations');
    }

}
