<?php

namespace App\Interfaces\Services;

interface UserServiceInterface
{
    public function registerUser($userDTO);
    public function login($email);
    public function loginCode($data);
    public function verifyEmail($token);
    public function codecheck($data);
}
