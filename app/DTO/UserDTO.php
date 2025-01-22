<?php

namespace App\DTO;

class UserDTO
{
    /**
     * Create a new class instance.
     */
    public $name;
    public $email;
    public $password;
    public $phone_number;
    public function __construct($name, $email, $password, $phone_number)
    {
        $this->name = $name;
        $this->email = $email;
        $this->password = $password;
        $this->phone_number = $phone_number;
    }
}
