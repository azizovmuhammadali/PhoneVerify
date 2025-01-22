<?php

namespace App\Reposities;

use App\Models\User;
use App\Traits\ResponseTrait;
use App\Http\Resources\UserResource;
use Illuminate\Support\Facades\Http;
use App\Interfaces\Reposities\UserReposityInterface;

class UserReposity implements UserReposityInterface
{
    use ResponseTrait;
    /**
     * Create a new class instance.
     */
    public function createUser($data)
    {
        $user = new User();
        $user->name = $data["name"];
        $user->email = $data["email"];
        $user->password = bcrypt($data["password"]);
        $user->phone_number = $data["phone_number"];
        $user->phone_code = rand(10000, 99999);
        $user->verification_token = $data['verification_token'];
        $user->save();
        $this->sms($user->phone_number);
        return $user;
    }
    public function loginUser($data)
    {
        $user = User::where('email', $data)->first();
        return $user;
    }
    public function loginCodeUser($code)
    {
        $user = User::where('phone_number', $code)->first();
        $user->phone_code = rand(10000, 99999);
        $user->save();
        $this->sms($user->phone_number);
        return $user;
    }
    public function code($data)
    {
        $user = User::where('phone_number', $data)->first();

        if (!$user) {
            return $this->error(__('errors.phoned'), 400);
        }
        $user->phone_verified_at = now();
        $user->save();
        return $user;
    }
    public function findUserByToken($token)
    {
        $user = User::where('verification_token', $token)->first();
        $user->email_verified_at = now();
        $user->save();
        return $user;
    }
    public function sms($phone)
    {
        $user = User::where('phone_number', $phone)->first();

        if (!$user) {
            return response()->json(__('errors.notfound'), 404);
        }

        $response = Http::withHeaders([
            'Authorization' => 'Bearer ' . $this->getToken(),
        ])->post('notify.eskiz.uz/api/message/sms/send', [
                    'mobile_phone' => $user->phone_number,
                    'message' => "Afisha Market MCHJ Tasdiqlovchi kodni kiriting:" . $user->phone_code,
                    'from' => '4546',
                ]);
        return response()->json($response->json());
    }
    public function getToken()
    {
        return "eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJleHAiOjE3Mzk5NjM1MTksImlhdCI6MTczNzM3MTUxOSwicm9sZSI6InVzZXIiLCJzaWduIjoiNDA4Yzg5YWNhODhhMDZkODJhZDEwMDZkNjUzMzMzYmM1YjIzNzI2MzU2ZTEzZmE0NGJkMjE1YWViZTNiNGQwOCIsInN1YiI6IjM2MTYifQ.5fDNRTc6DKd4DfMg7-Z7JJOEmqTsdbFupzydidcmGAk";
    }
}
