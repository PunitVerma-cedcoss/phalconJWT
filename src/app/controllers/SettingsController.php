<?php

use Phalcon\Mvc\Controller;


class SettingsController extends Controller
{
    public function indexAction()
    {
        $this->logger->info("some info test");
        $settings = new Settings();
        $this->view->data = $settings::findFirst('1');
        // IF GOT POST
        if ($this->request->ispost()) {
            $postData = $this->request->getpost();
            echo "<pre>";
            print_r($postData);
            echo "</pre>";
            $setting = new Settings();
            $save = $setting::findFirst('1');
            $save = $save->assign(
                [
                    "title_optimization" => $postData['titleopti'],
                    "default_price" => $postData['dprice'],
                    "default_stock" => $postData['dstock'],
                    "default_zipcode" => $postData['dzipcode'],
                ]
            );
            if ($save->save()) {
                $this->view->message = [
                    "type" => "success",
                    "message" => "Settings saved sucessfully"
                ];
            } else {
                $this->view->message = [
                    "type" => "error",
                    "message" => "some error occured"
                ];
            }
            $settings = new Settings();
            $this->view->data = $settings::findFirst('1');
            // die();
        }
    }
}
