<?php

namespace App\Components;

use Phalcon\Di\Injectable;

class Producstcomponent extends Injectable
{
    public function onCreate($data)
    {
        $eventsManager = $this->EventsManager;
        return $eventsManager->fire('productlistener:onCreate', $this, $data);
    }
}
