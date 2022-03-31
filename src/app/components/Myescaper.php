<?php

namespace App\Components;

use Phalcon\Escaper;

class Myescaper
{
    public function sanitize($var)
    {
        $escaper = new Escaper();
        return $escaper->escapeHtml($var);
    }
}
