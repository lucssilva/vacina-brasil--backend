<?php

namespace App\Http\Controllers\API;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\{Auth, Validator};

class AuthController extends _BaseController
{
    public function login(Request $request)
    {
        try {
            $user = User::firstWhere('cpf', $request->cpf);
            $success['token'] =  $user->createToken('LaravelSanctumAuth')->plainTextToken;

            return $this->handleResponse($success, 'User logged-in!');
        } catch (\Throwable $th) {
            //throw $th;
            return $this->handleError('Unauthorised.', ['error' => 'Unauthorised']);
        }
    }

    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'cpf' => 'required',
            'phone_number' => 'required',
        ]);

        if ($validator->fails()) return $this->handleError($validator->errors());


        $user = User::updateOrCreate(
            ['cpf' => $request->cpf],
            ['name' => $request->name, 'phone_number' => $request->phone_number],
        );
        $success['token'] =  $user->createToken('LaravelSanctumAuth')->plainTextToken;

        return $this->handleResponse($success, 'User successfully registered!');
    }
}
