<?php

namespace App\Components;

use Phalcon\Di\Injectable;

class Orderscomponent extends Injectable
{
    public function onCreate($data)
    {
        $eventsManager = $this->EventsManager;
        return $eventsManager->fire('orderlistener:onCreate', $this, $data);
    }
}
