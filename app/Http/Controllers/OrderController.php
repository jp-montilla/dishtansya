<?php

namespace App\Http\Controllers;
use App\Http\Requests\OrderRequest;
use App\Mail\OrderMail;
use App\Repositories\OrderRepo;
use App\Repositories\ProductRepo;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    protected $orderRepo;

    public function __construct(OrderRepo $orderRepo, ProductRepo $productRepo)
    {
        $this->orderRepo = $orderRepo;
        $this->productRepo = $productRepo;
    }
    
    public function store(OrderRequest $request)
    {
        $fields = $request->validated();
        
        foreach ($fields['products'] as $product)
        {
            $product_data = $this->productRepo->findById($product['product_id']);

            if ($product_data->available_stock < $product['quantity'])
            {
                return response([
                    'message' => 'Failed to order '.$product_data->name.' due to unavailability of the stock',
                ], 400);
            }
        }

        $order = $this->orderRepo->create();

        $order->products()->attach($this->orderRepo->mapProducts($fields['products']));

        \Mail::to($order->user->email)->send(new OrderMail($order));

        return response([
            'message' => 'You have successfully ordered this products.',
        ], 201);
    }

    


}
