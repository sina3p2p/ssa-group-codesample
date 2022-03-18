<?php

namespace App\Domains\Book\Models;

use App\Domains\Book\Helpers\BookDetailCast;
use App\Domains\CascadeDiscount\Models\CascadeDiscount;
use App\Domains\CascadeDiscount\Models\CascadeDiscountBook;
use App\Domains\Category\Models\Category;
use App\Domains\OneC\Models\OnecItem;
use App\Domains\Rate\Models\Rate;
use App\Domains\WishList\Models\WishList;
use App\Helpers\EloquentImageMutatorTrait;
use Astrotomic\Translatable\Translatable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class Book extends Model
{
    use HasFactory, EloquentImageMutatorTrait, Translatable;

    const BLOCK = [
        1, 2, 3, 4
    ];

    const SORTABLE = [
        'created_at', 'updated_at',
    ];

    public $translatedAttributes = ['name', 'description'];

    protected $guarded = [
        'id'
    ];

    protected $casts = [
        'pictures' => 'array',
        'details'  => BookDetailCast::class
    ];

    protected $appends = ["min_picture"];

    public $image_fields = ['pictures', 'cover'];

    public function scopeWithRate($query)
    {
        return  $query
                ->leftJoin('rates', function ($join) {
                    $join->on("rates.ratable_id", "books.id")
                        ->where("rates.ratable_type", Book::class);
                })
                ->addSelect(DB::raw('AVG(rates.rate) as rate'))
                ->groupBy('books.id');
    }

    public function scopeWithTransalationsJoin($query)
    {
        return $query->join('book_translations', function ($join)
        {
            $join->on('book_translations.book_id', '=', 'books.id')->where('locale', app()->getLocale());
        });
    }

    public function scopeWithPrice($query)
    {
        return $query->join('book_variations', function ($join)
        {
            $join->on('book_variations.book_id', '=', 'books.id');
        })->addSelect(DB::raw('MIN(book_variations.price) as p'));
    }


    public function scopeWithVariationStatus($query)
    {
        // $newQuery = $query;
        // foreach(array_keys(BookVariation::Variations) as $v){
        //     $newQuery->leftJoin('book_variations as '.$v, function($q) use ($v){
        //         $q->on($v.'.book_id', '=', 'books.id')
        //             ->where($v.'.variation', '=', $v);
        //     })
        //     ->addSelect(DB::raw('COUNT('.$v.'.id) as '.$v));
        // }

        // return $newQuery;
    }

    public function scopeWithVariation($query, $loadSpecification = false)
    {
        return $query->with(['variations' => function ($q) use ($loadSpecification)
        {
            $q->selectRaw('
                book_variations.*,
                case when (
                    discount > 0 and
                    (start_discount <= CURDATE() or start_discount is null) and
                    (end_discount >= CURDATE() or end_discount is null)
                ) then discount
                else 0 end
                as discount,
                case when ( books.is_palitra = 1) then stock_count
                else 10000 end
                as stock_count'
            )->join('books', 'book_variations.book_id','=','books.id');

            if($loadSpecification)
            {
                $q->with(['specs' =>  function ($query)
                {
                    $query->whereHas('element', function ($elQ)
                    {
                        // $elQ->whereRaw('specification_els.type = book_variations.type');
                    })->with('element');
                }]);
            }
        }]);
    }

    public function getLegacyImgAttribute()
    {
        if(isset($this->attributes['legacy_img']) && $this->attributes['legacy_img'])
        {
            return $this->attributes['legacy_img'];
        }


        $pic = $this->getAttribute('pictures');
        if($pic && is_array($pic) && count($pic))
        {
            return url(Storage::url($pic[0]));
        }


        return null;
    }


    public function getMinPictureAttribute()
    {
        if(isset($this->attributes['min_picture']) && $this->attributes['min_picture'])
        {
            return url(Storage::url($this->attributes['min_picture']));
        }

        $pic = $this->getAttribute('pictures');
        if($pic && is_array($pic) && count($pic))
        {
            return url(Storage::url($pic[0]));
        }

        return null;
    }

    public function variations()
    {
        return $this->hasMany(BookVariation::class);
    }

    public function author()
    {
        return $this->belongsTo(Author::class);
    }

    public function series()
    {
        return $this->belongsTo(Serie::class, 'serie_id');
    }

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function rates()
    {
        return $this->morphMany(Rate::class, 'ratable');
    }

    public function wishes()
    {
        return $this->hasMany(WishList::class, 'product_id', 'id');
    }

    public function discounts()
    {
        return $this->morphToMany(Discount::class, 'discountable');
    }

    public function onec_item()
    {
        return $this->belongsTo(OnecItem::class, 'isbn', 'isbn');
    }

    public function cascadeDiscounts()
    {
        return $this->belongsToMany(CascadeDiscount::class, 'cascade_discount_book');
    }

    public function cascadeDiscountBooks()
    {
        return $this->hasMany(CascadeDiscountBook::class);
    }
}
