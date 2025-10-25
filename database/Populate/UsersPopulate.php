<?php

namespace Database\Populate;

use App\Models\User;

class UsersPopulate
{
    public static function populate()
    {
        // Usuário admin
        $adminData = [
            'name' => 'admin',
            'email' => 'admin@gmail.com',
            'password' => '123456',
            'password_confirmation' => '123456',
            'phone' => '123456789', 
            'role' => 'admin',
        ];

        $admin = new User($adminData);
        $admin->save();

        // Usuários comuns
        $numberOfUsers = 10;

        for ($i = 1; $i <= $numberOfUsers; $i++) {
            $userData = [
                'name' => 'Fulano ' . $i,
                'email' => 'fulano' . $i . '@example.com',
                'password' => '123',
                'password_confirmation' => '123',
                'phone' => '12345678' . str_pad($i, 2, '0', STR_PAD_LEFT), 
                'role' => 'user', 
            ];

            $user = new User($userData);
            $user->save();
        }

        echo "Users populated with " . ($numberOfUsers + 1) . " registers\n";
    }
}