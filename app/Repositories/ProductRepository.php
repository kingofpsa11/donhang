<?php

namespace App\Repositories;

use App\Product;

class ProductRepository
{
    protected $product;

    public function __construct(Product $product)
    {
        $this->product = $product;
    }

    public function find($id, array $relations = [])
    {
        return $this->with($relations)->find($id);
    }

    public function with(array $relations)
    {
        return $this->product->with($relations);
    }

    public function create(array $attributes)
    {
        return $this->product->create($attributes);
    }

    public function existCode($code)
    {
        return $this->product->where('code', $code)->count();
    }

    public function update($attributes, $id)
    {
        return $this->product->find($id)->update($attributes);
    }
}