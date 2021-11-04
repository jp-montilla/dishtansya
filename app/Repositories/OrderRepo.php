<?php

namespace App\Repositories;

use App\Models\Order;

class OrderRepo
{

    protected $order;

    public function __construct(Order $order)
    {
        $this->order = $order;
    }

    public function create($data)
    {
        $data['user_id'] = auth()->user()->id;
        return $this->order->create($data);
    }

}