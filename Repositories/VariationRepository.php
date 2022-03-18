<?php

namespace App\Domains\Book\Repositories;

use App\Domains\Book\Models\BookVariation;
use App\Domains\Book\Helpers\ValidatedVariationDTO;
use App\Domains\OneC\Services\OneCService;
use App\Domains\Poste\Helpers\DeliveryPrice\DeliveryPriceDTO;
use Illuminate\Database\Eloquent\Collection;

class VariationRepository
{
    protected BookVariation $model;

    public function __construct(BookVariation $model)
    {
        $this->model = $model;
    }

    public function getAll()
    {
        return $this->model->get();
    }

    public function store(ValidatedVariationDTO $dto)
    {
        foreach($dto->specifications ?? [] as $spec)
        {

            $variation = $this->model->updateOrCreate(
                ['id' => data_get($spec, 'id', 0)],
                $dto->toArray()
            );

            $last_update = time();

            foreach($spec as $key => $value){
                if(in_array($key, ['vendor_id', 'price']))
                {
                    $variation->{$key} = $value;
                }
                $variation->specs()->updateOrCreate(
                    ['field' => $key],
                    ['value' => $value, 'last_update' => $last_update]
                );
            }

            $variation->specs()->where('last_update', '<>', $last_update)->delete();            

            $variation->save();

        }

        $this->model->where('book_id', $dto->book_id)->where('last_update', '<>', $dto->last_update)->delete();

        return $variation;
    }

    public function findByID(int $id) : BookVariation
    {
        return $this->model->where('id', $id)->first();
    }

   
    public function deleteByID(int $id)
    {
        $variation = $this->model->where('id', $id)->first();
        $variation->specs()->delete();
        $variation->delete();
    }

}
