<?php

namespace App\Controllers;

use Core\Http\Request;
use App\Services\Auth;
use Lib\FlashMessage;
use App\Models\User;

class AuthController
{
    public function showRegistrationForm(Request $request): void
    {
        $this->view('auth/register');
    }

public function register(Request $request)
{

    $name  = trim($request->getParam('name') ?? '');
    $email = trim($request->getParam('email') ?? '');
    $phone = trim($request->getParam('phone') ?? '');
    $password = trim($request->getParam('password') ?? '');
    $password_confirmation = trim($request->getParam('password_confirmation') ?? '');

    $errors = [];

    // Validação de nome
    if (empty($name)) {
        $errors['name'] = "O nome é obrigatório.";
    }

    // Validação de email
    if (empty($email)) {
        $errors['email'] = "O email é obrigatório.";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors['email'] = "O email informado não é válido.";
    } elseif (User::findByEmail($email)) {
        $errors['email'] = "Este email já está em uso.";
    }

    // Validação de telefone
    if (empty($phone)) {
        $errors['phone'] = "O telefone é obrigatório.";
    } elseif (!preg_match('/^\+?[0-9\s\-]{8,15}$/', $phone)) {
        $errors['phone'] = "O telefone informado não é válido.";
    }

    // Validação de senha
    if (empty($password)) {
        $errors['password'] = "A senha é obrigatória.";
    } elseif (strlen($password) < 6) {
        $errors['password'] = "A senha deve ter pelo menos 6 caracteres.";
    }

    // Validação de confirmação de senha
    if ($password !== $password_confirmation) {
        $errors['password_confirmation'] = "A confirmação de senha não corresponde.";
    }

    // Se houver erros, retorna para o formulário com mensagens
    if (!empty($errors)) {
        $user = new User();
        $user->name = $name;
        $user->email = $email;
        $user->phone = $phone;

        return $this->view('auth/register', [
            'user'   => $user,
            'errors' => $errors
        ]);
    }

    $user = new User();
    $user->name = $name;
    $user->email = $email;
    $user->phone = $phone;
    $user->password = $password;
    $user->password_confirmation = $password_confirmation;
    $user->role = 'user';

    if ($user->save()) {
        FlashMessage::success('Registro realizado com sucesso! Efetue o login.');
        header('Location: /login');
    } else {
        $errors = $user->errors ?? FlashMessage::danger(implode("<br>", $errors));
        header('Location: /register');
    }
}


    public function showLoginForm(Request $request): void
    {
     $this->view('auth/login', ['title' => 'login']);
    }

    public function login(Request $request): void
    {
        $email = $request->getParam('email');
        $password = $request->getParam('password');

        if (empty($email) || empty($password)) {
            FlashMessage::danger('Preencha todos os campos.');
            header('Location: /login');
            exit();
        }
        
        $user = User::findByEmail($email);
        
        if ($user && $user->authenticate($password)) {
            Auth::login($user);
            FlashMessage::success('Login realizado com sucesso!');

            if($user->role === 'admin'){
                header('Location: /admin/dashboard');
            } else {
                header('Location: /user/dashboard');
            }

        } else {
            FlashMessage::danger('Credenciais inválidas.');
            header('Location: /login');
        }
    }

    public function logout(Request $request): void
    {
        Auth::logout();
        FlashMessage::success('Você saiu da sua conta.');
        header('Location: /');
        exit;
    }

    protected function view(string $viewName, array $data = []): void
{
    $view = __DIR__ . '/../views/' . $viewName . '.phtml';
    extract($data);
    require __DIR__ . '/../views/layouts/application.phtml';
}
}
