<?php

namespace App\Repositories;

use App\Models\Product;

class ProductRepo
{

    protected $product;

    public function __construct(Product $product)
    {
        $this->product = $product;
    }

    public function findById($productId)
    {
        return $this->product->find($productId);
    }

    public function updateProductStock($productId, $quantity)
    {
        $product = $this->findById($productId);
        
        return $product->update([
            'available_stock' => $product->available_stock - $quantity,
        ]);
    }

}