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

    public function create($data){
        $this->order->create($data);
    }

}