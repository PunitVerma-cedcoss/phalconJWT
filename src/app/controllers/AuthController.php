<?php

use Phalcon\Mvc\Controller;


class AuthController extends Controller
{
    public function indexAction()
    {
        $permissions = new Permissions();
        $roleData = $permissions::find();
        $this->view->data = $roleData;
        // if got post 
        if ($this->request->isPost()) {
            echo "<pre>";
            print_r($this->request->getPost());
            echo "</pre>";

            // save data to db
            $users = new Users();
            $saveresp = $users->assign(
                [
                    'email' => $this->request->getPost()['email'],
                    'password' => $this->request->getPost()['password'],
                    'role' => $this->request->getPost()['selectrole'],
                ]
            );
            if ($saveresp->save()) {
                // echo "saved";
                $now = new DateTimeImmutable();
                $jwtinit = new \App\Components\JwtInit();
                $token = $jwtinit->init($this->request->getPost()['selectrole'], $now);
                $saveresp->assign(
                    [
                        'token' => $token
                    ]
                );
                $saveresp->save();
                header("location:/admin?bearer=" . $token);
                // $this->view->token = $token;
            } else {
                echo "error";
            }
            // die();
        }
    }
}
