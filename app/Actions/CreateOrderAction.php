<?php

namespace App\Actions;

use App\Models\Order;
use App\Options\ModelResourceStatus;

class CreateOrderAction
{
    public function executeOrderCreation( array $data):? Order
    {
       return Order::create(
            [
                'user_id' => auth()->id(),
                'price' => $data['price'],
                'delivery_charge' => $data['delivery_charge'],
                'delivery_location' => $data['delivery_location'],
                'status' => ModelResourceStatus::PENDING
            ]
        );
    }
}
