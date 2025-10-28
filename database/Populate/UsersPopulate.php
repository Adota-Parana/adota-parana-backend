<?php

namespace Database\Populate;

use App\Models\User;

class UsersPopulate
{
    public static function populate()
    {
        // Função auxiliar para criar o usuário
        $createUser = function ($data) {
            $user = new User();

            $user->name  = $data['name'];
            $user->email = $data['email'];
            $user->phone = $data['phone'];
            $user->role  = $data['role'];

            // Criptografando a senha
            $user->encrypted_password = password_hash($data['password'], PASSWORD_DEFAULT);

            $user->save();
        };

        // Usuário admin
        $adminData = [
            'name' => 'admin',
            'email' => 'admin@gmail.com',
            'password' => '123', // será criptografada
            'phone' => '4299999999', 
            'role' => 'admin',
        ];

        $createUser($adminData);

        // Usuários comuns
        $numberOfUsers = 50;

        for ($i = 1; $i <= $numberOfUsers; $i++) {
            $userData = [
                'name' => 'Fulano ' . $i,
                'email' => 'fulano' . $i . '@example.com',
                'password' => '123', // será criptografada
                'phone' => '12345678' . str_pad($i, 2, '0', STR_PAD_LEFT), 
                'role' => 'user', 
            ];

            $createUser($userData);
        }

        echo "Users populated with " . ($numberOfUsers + 1) . " registers\n";
    }
}
