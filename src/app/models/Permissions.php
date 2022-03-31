<?php

use Phalcon\Mvc\Model;

class Permissions extends Model
{
    public $id;
    public $role_name;
    public $permissions;
    public function initilize()
    {
        $this->hasOne(
            'id',
            'Users',
            'role'
        );
    }
}
