<?php

namespace App\Actions;

use App\Models\User;
use App\Options\ModelResourceStatus;
use Illuminate\Support\Facades\Hash;

class CreateUserAction
{
    public function executeUserCreation( array $data):? User
    {
       return User::create(
            [
                'name' => $data['name'],
                'email' => $data['email'],
                'password' => Hash::make($data['password']),
                'status' => ModelResourceStatus::PENDING
            ]
        );
    }
}
