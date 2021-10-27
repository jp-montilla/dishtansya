<?php

namespace App\Http\Controllers;
use App\Http\Requests\OrderRequest;
use App\Models\Order;
use App\Models\Product;
use App\Repositories\OrderRepo;

use Illuminate\Http\Request;

class OrderController extends Controller
{
    protected $orderRepo;

    public function __construct(OrderRepo $orderRepo)
    {
        $this->orderRepo = $orderRepo;
    }


    public function store(OrderRequest $request){
        $fields = $request->validated();

        $product = Product::find($fields['product_id']);

        if ($product->available_stock < $fields['quantity']){
            return response([
                "message" => "Failed to order this product due to unavailability of the stock",
            ], 400);
        }

        $remaining_stock = $product->available_stock - $fields['quantity'];
        $product->update([
            'available_stock' => $remaining_stock,
        ]);

        $this->orderRepo->create([
            'user_id' => auth()->user()->id,
            'product_id' => $product->id,
            'quantity' => $fields['quantity'],
        ]);

        // Order::create([
        //     'user_id' => auth()->user()->id,
        //     'product_id' => $product->id,
        //     'quantity' => $fields['quantity'],
        // ]);

        return response([
            "message" => "You have successfully ordered this product.",
        ], 201);
    }
}
