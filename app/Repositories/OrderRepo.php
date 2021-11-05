<?php

namespace App\Repositories;

use App\Models\Order;
use App\Models\Product;

class OrderRepo
{

    protected $order;

    public function __construct(Order $order)
    {
        $this->order = $order;
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
                $product_data = Product::find($product['product_id']);
                $remaining_stock = $product_data->available_stock - $product['quantity'];
                $product_data->update([
                    'available_stock' => $remaining_stock,
                ]);

                return [
                    'product_id' => $product['product_id'],
                    'quantity' => $product['quantity'],
                ];
            });
    }

}