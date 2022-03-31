<?php

use Phalcon\Mvc\Controller;


class ProductController extends Controller
{
    public function indexAction()
    {
        $product = new Products();
        $this->view->data = $product::find();
    }
    public function addAction()
    {
        // if got post 
        if ($this->request->isPost()) {
            $postData = $this->request->getpost();
            $producttrigger = new App\Components\Producstcomponent();
            $modifiedPostData = $producttrigger->onCreate($postData);
            echo "<pre>";
            print_r($modifiedPostData);
            echo "</pre>";

            $product = new Products();
            $save = $product->assign(
                [
                    "product_name" => $modifiedPostData["name"],
                    "product_desc" => $modifiedPostData["description"],
                    "product_tags" => $modifiedPostData["tags"],
                    "product_price" => $modifiedPostData["price"],
                    "product_stock" => $modifiedPostData["stock"],
                ]
            );
            if ($save->save()) {
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
