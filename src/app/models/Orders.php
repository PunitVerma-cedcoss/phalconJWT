<?php

use Phalcon\Mvc\Model;

class Orders extends Model
{
    public $id;
    public $customer_name;
    public $customer_address;
    public $zipcode;
    public $product_id;
    public $quantity;

    public function initilize()
    {
        $this->hasOne(
            'product_id',
            'Products',
            'id'
        );
    }
}
