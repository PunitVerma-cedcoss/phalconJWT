<?php

namespace App\Listeners;

use Phalcon\Di\Injectable;
use Phalcon\Events\Event;

class Notificationslistener extends Injectable
{
    public function beforeHandleRequest(Event $event, \Phalcon\Mvc\Application $application)
    {
        $aclFile = APP_PATH . '/security/acl.cache';

        // get controller and action name
        $getData = explode("/", $this->request->getQuery()['_url']);
        // $controllerName = $getData[1];
        // $actionName = isset($getData[2]) ? $getData[2] : 'index';
        $controllerName = $this->router->getControllerName();
        $actionName = strlen($this->router->getActionName()) != 0 ? $this->router->getActionName() : 'index';
        $role = $application->request->get('bearer');
        // now check in db for valid creds
        //if acl file exists
        // if login screen is hit , just proceed 💱
        if ($controllerName != 'auth') {
            if (!isset($role)) {
                die("please prodive bearer as a token");
            }
            // call validator of token
            $validator = new \App\Components\JwtInit();
            $resp = $validator->jwtValidate($role);
            if ($resp) {
                if (is_file($aclFile)) {
                    $acl = unserialize(file_get_contents($aclFile));
                    $role = $resp;
                    if (!$acl->isAllowed($role, $controllerName, $actionName)) {
                        // header('location:/');
                        echo "Acess Denied " . $role;
                        die();
                    }
                }
            }
        }
        // die("");
    }
}
