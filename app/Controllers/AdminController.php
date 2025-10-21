<?php

namespace App\Controllers;

use Core\Http\Request;
use App\Services\Auth;
use Lib\FlashMessage;
use App\Models\User;

class AdminController
{
    public function index(): string
    {
        $users = User::all();
        return $this->view('admin/index', ['users' => $users]);
    }

    public function usersDelete(Request $request)
    {
        $id = (int) $request->getParam('id');
        $userToDelete = User::findById($id);

        if ($userToDelete && $userToDelete->id != Auth::user()->id) {
            $userToDelete->destroy();
            FlashMessage::success('Usuário deletado!');
        } 
        else 
        {
            FlashMessage::danger('Não é possível deletar seu próprio usuário!');
        }

        return header('Location: /admin/index');
    }

    protected function view(string $path, array $data = []): string
    {
        extract($data);
        $user = Auth::user();
        return include __DIR__ . "/../views/{$path}.php";
    }
}
