<?php

namespace Database\Populate;

use App\Models\User;

class UsersPopulate
{
    public static function populate()
    {
        $data =  [
            'name' => 'admin',
            'email' => 'admin@gmail.com',
            'password' => '123456',
            'password_confirmation' => '123456',
            'phone' => '123456789', 
            'role' => 'user'
        ];

        $user = new User($data);
        $user->save();

        $numberOfUsers = 10;

        for ($i = 1; $i < $numberOfUsers; $i++) {
            $data =  [
                'name' => 'Fulano ' . $i,
                'email' => 'fulano' . $i . '@example.com',
                'password' => '123',
                'password_confirmation' => '123',
                'phone' => '123456789',
                'role' => 'user'
            ];

            $user = new User($data);
            $user->save();
        }

        echo "Users populated with $numberOfUsers registers\n";
    }
}
