<?php

namespace App\Http\Controllers;
use App\Http\Requests\OrderRequest;
use App\Mail\MyTestMail;
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

    // public function store(OrderRequest $request){
    //     $fields = $request->validated();

    //     $product = Product::find($fields['product_id']);

    //     if ($product->available_stock < $fields['quantity'])
    //     {
    //         return response([
    //             'message' => 'Failed to order this product due to unavailability of the stock',
    //         ], 400);
    //     }

    //     $remaining_stock = $product->available_stock - $fields['quantity'];
    //     $product->update([
    //         'available_stock' => $remaining_stock,
    //     ]);

    //     $order = $this->orderRepo->create([
    //         'product_id' => $product->id,
    //         'quantity' => $fields['quantity'],
    //     ]);

    //     \Mail::to(auth()->user()->email)->send(new MyTestMail($order));

    //     return response([
    //         'message' => 'You have successfully ordered this product.',
    //     ], 201);
    // }

    public function store(OrderRequest $request)
    {
        $fields = $request->validated();

        // $order = $this->orderRepo->create();

        $products = $this->orderRepo->mapProducts($fields['products']);
        dd($products);

        // $order->products()->sync($this->orderRepo->mapProducts($products));

        $product = Product::find($fields['product_id']);

        if ($product->available_stock < $fields['quantity'])
        {
            return response([
                'message' => 'Failed to order this product due to unavailability of the stock',
            ], 400);
        }

        $remaining_stock = $product->available_stock - $fields['quantity'];
        $product->update([
            'available_stock' => $remaining_stock,
        ]);

        \Mail::to(auth()->user()->email)->send(new MyTestMail($order));

        return response([
            'message' => 'You have successfully ordered this product.',
        ], 201);
    }

    


}
