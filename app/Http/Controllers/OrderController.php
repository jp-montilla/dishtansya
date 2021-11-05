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
    
    public function store(OrderRequest $request)
    {
        $fields = $request->validated();
        
        foreach ($fields['products'] as $product){
            $product_data = Product::find($product['product_id']);
            if ($product_data->available_stock < $product['quantity'])
            {
                return response([
                    'message' => 'Failed to order '.$product_data->name.' due to unavailability of the stock',
                ], 400);
            }
        }

        $order = $this->orderRepo->create();

        $order->products()->attach($this->orderRepo->mapProducts($fields['products']));

        return response([
            'message' => 'You have successfully ordered this products.',
        ], 201);
    }

    


}
