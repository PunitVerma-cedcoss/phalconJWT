<?php

use Phalcon\Mvc\Controller;


class AdminController extends Controller
{
    public function indexAction()
    {
        $this->assets->addJs('js/role.js');
        $list = new App\Components\Utilscomponent();
        $users = new Permissions();
        $this->view->data2 = $users::find();
        $this->view->token = $this->request->getQuery()['bearer'];
        $this->view->data = $list->getList();
        if ($this->request->isPost()) {
            $controllers = [];
            $actions = [];
            $aclData = [];
            foreach (array_keys($this->request->getPost()) as $keys) {
                if ($keys != 'name') {
                    $data = explode("<->", $keys);
                    $controller = substr($data[0], 0, strlen($data[0]) - strlen('Controller'));
                    $action = $data[1];
                    array_push($controllers, $controller);
                    array_push($actions, $action);
                    // echo $controller . "->" . $action . '<br>';
                    // $roles[$controller] = []
                }
            }
            foreach ($controllers as $c) {
                $aclData[$c] = [];
                foreach (array_keys($this->request->getPost()) as $i) {
                    if (preg_match("/{$c}/", $i)) {
                        array_push($aclData[$c], explode("Action", explode('<->', $i)[1])[0]);
                    }
                }
            }

            // push the acl data into db
            $permission = new Permissions();
            $s = $permission->assign(
                [
                    'role_name' => $this->request->getPost()['name'],
                    'permissions' => json_encode($aclData)
                ]
            );
            // echo "<pre>";
            // print_r($this->request->getPost()['name']);
            // echo "</pre>";
            // die();
            if ($s->save()) {
                header("location:/secure/mkACL?bearer=" . $this->request->getQuery()['bearer']);
            }
        }
    }
    public function viewrolesAction()
    {
    }
    public function editAction()
    {
        if (isset($this->request->getquery()['name'])) {
            $this->assets->addJs('js/role.js');
            $list = new App\Components\Utilscomponent();
            $users = new Permissions();
            $this->view->data = $list->getList();
            $this->view->token = $this->request->getQuery()['bearer'];
            $this->view->name = $users::findFirst(
                [
                    'conditions' => 'role_name = :name:',
                    'bind' => [
                        'name' => $this->request->getQuery()["name"]
                    ]
                ]
            )->role_name;
            $this->view->data2 = json_decode($users::findFirst(
                [
                    'conditions' => 'role_name = :name:',
                    'bind' => [
                        'name' => $this->request->getQuery()["name"]
                    ]
                ]
            )->permissions);
        }


        if ($this->request->isPost()) {
            // echo "<pre>";
            // print_r($this->request->getPost());
            // echo "</pre>";
            // die();
            $controllers = [];
            $actions = [];
            $aclData = [];
            foreach (array_keys($this->request->getPost()) as $keys) {
                if ($keys != 'name') {
                    $data = explode("<->", $keys);
                    print_r($data);
                    $controller = substr($data[0], 0, strlen($data[0]) - strlen('Controller'));
                    $action = $data[1];
                    array_push($controllers, $controller);
                    array_push($actions, $action);
                    // echo $controller . "->" . $action . '<br>';
                    // $roles[$controller] = []
                }
            }
            foreach ($controllers as $c) {
                $aclData[$c] = [];
                foreach (array_keys($this->request->getPost()) as $i) {
                    if (preg_match("/{$c}/", $i)) {
                        array_push($aclData[$c], explode("Action", explode('<->', $i)[1])[0]);
                    }
                }
            }

            // push the acl data into db
            $permission = new Permissions();
            $perm = $permission::findFirst(
                [
                    'conditions' => 'role_name = :name:',
                    'bind' => [
                        'name' => $this->request->getPost()["name"]
                    ]
                ]
            );
            $s = $perm->assign(
                [
                    'role_name' => $this->request->getPost()['name'],
                    'permissions' => json_encode($aclData)
                ]
            );
            if ($s->save()) {
                // header("location:/secure/mkACL?role=admin");
                echo "saved";
            } else {
                echo "error";
            }
            header("location:/secure/mkACL?bearer=" . $this->request->getQuery()['bearer']);
            // die("died");
        }
    }
}
