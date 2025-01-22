<?php

namespace App\Services;

use Illuminate\Support\Str;
use App\Traits\ResponseTrait;
use App\Http\Resources\UserResource;
use Illuminate\Support\Facades\Hash;
use App\Interfaces\Services\UserServiceInterface;
use App\Interfaces\Reposities\UserReposityInterface;

class UserService implements UserServiceInterface
{
    /**
     * Create a new class instance.
     */
    use ResponseTrait;
    public function __construct(protected UserReposityInterface $reposity)
    {
        //
    }
    public function registerUser($userDTO){
      $data = [
        'name' => $userDTO->name,
        'email'=> $userDTO->email,
       'password'=> $userDTO->password,
         'phone_number'=> $userDTO->phone_number,
         'verification_token' => Str::random(20),
      ];
      return $this->reposity->createUser($data);
    }
    public function loginCode($data){
      return  $this->reposity->loginCodeUser($data);
  }
    public function login($email){
      $user = $this->reposity->loginUser($email['email']);
      if (!$user) {
          return $this->error(__('errors.not_found'));  
      }
      if (Hash::check($email['password'], $user->password)) {
          $token = $user->createToken('auth_login')->plainTextToken;
          return [
              'user' => $user,
              'token' => $token,
          ];
      } else {
          return $this->error(__('errors.login'));
      }
    }
    
    public function codecheck($code)
    {
        $user = $this->reposity->code($code['phone_number']);
        
        if (!$user) {
            return ['status' => 'error', 'message' => __('errors.phoned')];
        }
    
        if ($code['code'] != $user->phone_code) {
            return ['status' => 'error', 'message' => __('errors.phoned')];
        }
    
        $token = $user->createToken('auth_login')->plainTextToken;
    
        return [
            'status' => 'success',
            'user' => $user,
            'token' => $token
        ];
    }
 public function verifyEmail($token){
  return $this->reposity->findUserByToken($token);  
}
}

