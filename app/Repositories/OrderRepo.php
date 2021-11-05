<?php

namespace App\Repositories;

use App\Models\Order;
use App\Repositories\ProductRepo;

class OrderRepo
{

    protected $order;

    protected $productRepo;

    public function __construct(Order $order, ProductRepo $productRepo)
    {
        $this->order = $order;
        $this->productRepo = $productRepo;
    }

    public function create()
    {
        $data['user_id'] = auth()->user()->id;
        return $this->order->create($data);
    }

    public function mapProducts($products)
    {
        return collect($products)
            ->map(function($product)
            {
                $this->productRepo->updateProductStock($product['product_id'], $product['quantity']);

                return [
                    'product_id' => $product['product_id'],
                    'quantity' => $product['quantity'],
                ];
            });
    }

}