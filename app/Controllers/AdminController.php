<?php

namespace App\Controllers;

use Core\Http\Request;
use Core\Http\Controllers\Controller;
use App\Services\Auth;
use App\Models\User;
use Lib\FlashMessage;


class AdminController extends Controller
{
    public function index(): void
    {
        $users = User::all();
        $this->render('admin/index', ['users' => $users]);
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

        return header('Location: /admin/dashboard');
    }

}
