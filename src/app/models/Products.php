<?php

use Phalcon\Mvc\Model;

class Products extends Model
{
    public $id;
    public $product_name;
    public $product_desc;
    public $product_tags;
    public $product_price;
    public $product_stock;
}
