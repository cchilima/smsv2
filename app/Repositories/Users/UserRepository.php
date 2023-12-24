<?php

namespace App\Repositories\Users;

use Illuminate\Support\Facades\Hash;
use App\Models\Users\{ User, UserType };

class UserRepository
{
    public function create($data)
    {
        // Hash the password before creating the user
        $data['password'] = $this->encryptPassword($data['password']);

        return User::create($data);
    }

    public function getAll()
    {
        return User::paginate(20);
    }

    public function update($id, $data)
    {
        return User::find($id)->update($data);
    }

    public function find($id)
    {
        return User::find($id);
    }

    public function getUserTypes()
    {
        return UserType::all(['id', 'name']);
    }

    private function encryptPassword($password)
    {
         // Hash the password before creating the user
         $hashedPassword = Hash::make($password);

         return $hashedPassword;
    }
}
