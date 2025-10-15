<?php

namespace App\Controllers;

use Core\Http\Request;
use App\Services\Auth;
use Lib\FlashMessage;
use App\Models\User;

class AdminController
{
    public function index(Request $request): string
    {
        $stats = [
            $this->usersIndex(),
        ];

        return $this->view('admin/Dashboard', compact('stats'));
    }

    public function usersIndex(): string
    {
        $users = User::all();
        return $this->view('admin/users/index', ['users' => $users]);
    }

    public function usersDelete(int $id)
    {
        $user = User::find($id);

        if ($user && $user->id != Auth::user()->id) {
            $user->destroy();
            FlashMessage::success('Usuário deletado!');
        } else {
            FlashMessage::error('Não é possível deletar seu próprio usuário!');
        }

        return redirect('/admin/users');
    }

    protected function view(string $path, array $data = []): string
    {
        extract($data);
        $user = Auth::user();
        return include __DIR__ . "/../views/{$path}.php";
    }
}
