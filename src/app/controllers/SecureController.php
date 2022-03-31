<?php

use Phalcon\Mvc\Controller;
use Phalcon\Acl\Adapter\Memory;
use Phalcon\Acl\Role;
use Phalcon\Acl\Component;


class SecureController extends Controller
{
    public function mkACLAction()
    {
        $permission = new Permissions();
        $dataDb = $permission::find();
        $aclFile = APP_PATH . '/security/acl.cache';
        $acl = new Memory();
        $getList = new App\Components\Utilscomponent();


        // adding roles 🌏
        $roles_names = new Permissions();
        foreach ($roles_names::find() as $row) {
            // echo "adding role " . $row->role_name . "<br>";
            $acl->addRole($row->role_name);
            // adding componenets 🍿
            foreach ($getList->getList() as $k => $v) {
                $k = substr($k, 0, strlen($k) - strlen('Controller'));
                // print_r($k);
                $tmp = [];
                foreach ($v as $action) {
                    $tmp[] = substr($action, 0, strlen($action) - strlen('Action'));
                }
                // print_r($k);
                // print_r($tmp);
                $acl->addComponent(
                    strtolower($k),
                    $tmp,
                );
            }
            foreach ($tmp as $t) {
                $allowData = json_decode($row->permissions);
                foreach ($allowData as $adk => $adv) {
                    foreach ($adv as $j) {
                        $acl->allow($row->role_name, strtolower($adk), $j);
                        // echo "acl->allow('{$row->role_name}','{$adk}','{$j}')" . '<br>';
                    }
                }
            }
        }
        file_put_contents($aclFile, serialize($acl));
        // die();
        header("location:/admin?bearer=" . $this->request->getQuery()['bearer']);
    }

    public function BuildACLAction()
    {
        $aclFile = APP_PATH . '/security/acl.cache';
        if (!is_file($aclFile)) {
            $acl = new Memory();

            $acl->addRole('admin');
            $acl->addRole('guest');

            $acl->addComponent(
                'product',
                [
                    'index',
                    'add',
                ]
            );
            $acl->addComponent(
                'order',
                [
                    'index',
                    'add',
                ]
            );
            $acl->addComponent(
                'admin',
                [
                    'index',
                ]
            );
            $acl->allow('admin', '*', '*');
            $acl->deny('guest', '*', '*');
            file_put_contents($aclFile, serialize($acl));
        } else {
            $acl = unserialize(file_get_contents($aclFile));
            print_r($acl);
        }
        echo is_file($aclFile) ? 'file exists' . '<br>' : 'file not exist' . '<br>';
        echo $acl->isAllowed('admin', 'product', 'index') ? 'access granted' : 'access denied';

        echo "<br>";

        die();
    }
}
