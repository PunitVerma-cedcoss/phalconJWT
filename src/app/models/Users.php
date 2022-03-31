<?php

use Phalcon\Mvc\Model;

class Users extends Model
{
    public $id;
    public $email;
    public $password;
    public $role;
    public $token;

    public function initilize()
    {
        $this->hasOne(
            'role',
            'Permissions',
            'id'
        );
    }
}
