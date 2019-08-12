<?php

namespace App\Services;

use App\Repositories\ImageRepository;
use App\Repositories\ProductRepository;
use Illuminate\Http\Request;

class ProductService
{
    protected $productRepository;
    protected $imageRepository;

    public function __construct(ProductRepository $productRepository, ImageRepository $imageRepository)
    {
        $this->productRepository = $productRepository;
        $this->imageRepository = $imageRepository;
    }

    public function find($id)
    {
        return $this->productRepository->find($id, ['images']);
    }

    public function create(Request $request)
    {
        $product = $this->productRepository->create($request->except('file'));
        $this->imageRepository->create($request->file, $product->id);

        return $product;
    }

    public function update(Request $request, $id)
    {
        $this->productRepository->update($request->except('file'), $id);
        $this->imageRepository->create($request->file, $id);
    }

    public function existCode($code)
    {
        return $this->productRepository->existCode($code);
    }

    public function deleteImage($id)
    {
        return $this->imageRepository->delete($id);
    }
}