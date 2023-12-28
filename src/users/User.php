<?php

namespace David\PhpTest\users;

class User
{
    public $id;
    public $username;
    public $name;
    public $email;
    public $password;

    public function __construct($id, $username, $name, $email, $password)
    {
        $this->id = $id;
        $this->username = $username;
        $this->name = $name;
        $this->email = $email;
        $this->password = $password;
    }
}
