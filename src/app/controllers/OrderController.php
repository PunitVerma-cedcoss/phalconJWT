<?php

use App\Components\Orders;
use Orders as GlobalOrders;
use Phalcon\Mvc\Controller;

class OrderController extends Controller
{
    public function indexAction()
    {
        $orders = new GlobalOrders();
        $this->view->data = $orders::find();
    }
    public function addAction()
    {
        // fetch all products
        $products = new Products();
        $dbData = $products::find();
        // send data into view
        if ($dbData->Count() < 0) {
            // if db has no products then redirect to add product
            header("location:/product");
        }
        $this->view->data = $dbData;

        // if got post
        if ($this->request->ispost()) {
            $postData = $this->request->getpost();
            // print_r($postData);
            //order is being created fire the trigger 🧨
            $ordertrigger = new App\Components\Orderscomponent();
            $modifiedPostData = $ordertrigger->onCreate($postData);
            echo "<pre>";
            print_r($modifiedPostData);
            echo "</pre>";
            $order = new GlobalOrders();
            $order->assign([
                "customer_name" => $modifiedPostData["name"],
                "customer_address" => $modifiedPostData["address"],
                "product_id" => $modifiedPostData["product"],
                "zipcode" => $modifiedPostData["zipcode"],
                "quantity" => $modifiedPostData["quantity"]
            ]);
            if ($order->save()) {
                $this->view->message = [
                    "type" => "success",
                    "message" => "order placed sucessfully"
                ];
            } else {
                $this->view->message = [
                    "type" => "error",
                    "message" => "some error occured"
                ];
            }
            // die();
        }
    }
}
