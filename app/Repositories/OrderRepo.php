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
        return collect($products)->map(function($product)
        {
            $stock = Product::find($product["product_id"])->available_stock;
            $quantity = $stock < $product["quantity"] ? 0 : $product["quantity"];

            return [
                'product_id' => $product["product_id"],
                'quantity' => $quantity,
            ];
        });
    }

}