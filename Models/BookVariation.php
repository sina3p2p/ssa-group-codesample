<?php

namespace App\Domains\Book\Models;

use App\Domains\Book\Helpers\SpecificationCast;
use App\Domains\Book\Models\Specifications\AudioBookSpecifications;
use App\Domains\Book\Models\Specifications\EBookSpecifications;
use App\Domains\Book\Models\Specifications\PaperBookSpecifications;
use App\Domains\Category\Models\Specification;
use App\Domains\Payment\Models\Transaction;
use App\Domains\Payment\Models\TranscationProduct;
use App\Domains\Promocode\Models\Promocode;
use App\Domains\Promocode\Models\PromocodeProduct;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BookVariation extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    public function book()
    {
        return $this->belongsTo(Book::class);
    }

    public function specs()
    {
        return $this->hasMany(Specification::class, 'variation_id');
    }

    public function transactions()
    {
        return $this->belongsToMany(Transaction::class, 'transaction_products', 'product_id', 'id')->using(TranscationProduct::class);
    }

    public function promocode()
    {
        return $this->belongsToMany(Promocode::class, 'promocode_products', 'book_variation_id', 'promocode_id')->using(PromocodeProduct::class)->withPivot('discount');
    }

}
