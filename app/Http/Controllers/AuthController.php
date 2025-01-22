<?php

namespace App\Http\Controllers;

use App\DTO\UserDTO;
use App\Http\Requests\LoginCodeRequest;
use App\Http\Requests\LoginRequest;
use App\Jobs\SendSms;
use Illuminate\Http\Request;
use App\Traits\ResponseTrait;
use App\Http\Resources\UserResource;
use App\Http\Requests\RegisterRequest;
use App\Http\Requests\MobileCodeRequest;
use App\Interfaces\Services\UserServiceInterface;

class AuthController extends Controller
{
    use ResponseTrait;
    public function __construct(protected UserServiceInterface $userServiceInterface){}
    public function register(RegisterRequest $registerRequest){
        $userDTO = new UserDTO($registerRequest->name, $registerRequest->email, $registerRequest->password, $registerRequest->phone_number);
        $user = $this->userServiceInterface->registerUser($userDTO);
        SendSms::dispatch($user);
        return $this->success(new UserResource($user),__('successes.registered'),201);
    }  
    public function verifyEmail(Request $request){
        $message = $this->userServiceInterface->verifyEmail($request->token);
        return $this->success(new UserResource($message),__('successes.email'));
    }
    public function entercode(MobileCodeRequest $request){
        $data = [
            'phone_number' => $request->phone_number,
            'code' => $request->code
        ];
        $result = $this->userServiceInterface->codecheck($data);
       
        return $this->success([
            'user' =>new UserResource($result['user']),
            'token' => $result['token']
        ],__('successes.login'));
    }
    public function login(LoginRequest $request){
        $result = $this->userServiceInterface->login($request->all());
        if($result){
        return $this->success([
            'user' =>new UserResource($result['user']),
            'token' => $result['token']
        ],__('successes.login'));
    }
    else{
        return $this->error(__('errors.login'));
    }
}
public function LoginCode(LoginCodeRequest $request){
   $user = $this->userServiceInterface->loginCode($request->phone_number);
   return $this->success(new UserResource($user),__('successes.login'));
}
public function logout(Request $request){
    $request->user()->currentAccessToken()->delete();
    return $this->success([], __('success.logout'), 204);
}
    
}