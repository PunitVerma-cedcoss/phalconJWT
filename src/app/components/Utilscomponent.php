<?php

use Phalcon\Acl\Adapter\Memory as ACLMEM;
use Phalcon\Acl\Role;
use Phalcon\Acl\Component;

namespace App\Components;

use Permissions;

class Utilscomponent
{
    public function getList()
    {
        $controllers = [];

        foreach (glob(APP_PATH . '/controllers/*Controller.php') as $controller) {
            // echo $controller;
            $className = basename($controller, '.php');
            $controllers[$className] = [];
            $methods = (new \ReflectionClass($className))->getMethods(\ReflectionMethod::IS_PUBLIC);

            foreach ($methods as $method) {
                if (\Phalcon\Text::endsWith($method->name, 'Action')) {
                    $controllers[$className][] = $method->name;
                }
            }
        }
        return $controllers;
    }

}
