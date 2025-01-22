<?php

namespace App\Interfaces\Reposities;

interface UserReposityInterface
{
    public function createUser($data);
    public function loginUser($data);
    public function loginCodeUser($code);
    public function code($data);
    public function findUserByToken($token);
}
