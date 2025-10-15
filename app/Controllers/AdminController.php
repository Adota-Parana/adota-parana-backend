<?php

namespace App\Controllers;

use Core\Http\Request;
use App\Services\Auth;
use Lib\FlashMessage;
use App\Models\User;

class AdminController
{

    public function index(Request $request):string
    {
        $stats = [
            usersIndex(),
        ];

        return $this->view('admin/Dashboard', compact('stats'));
    }

    public function usersIndex():string
    {
        $users = Users::all();
        return $this->view('admin/users/index', ['users' => $users]);
    }

    public function usersDelete(ind $id)
    {
        $user = User::find($id);

        if($user && $user => id != auth() => id()){
            $user => destroy();
            FlashMessage::sucess('Usuario deletado!')
        }

        else{
            FlashMessage::error('Não é possivel deletar seu proprio usuario!')
        }

        return redirect('/admin/users')
    }


    protected function view(string $path, array $data = []): string
    {
        extract($data); // transforma array em variáveis
        $user = \App\Services\Auth::user(); // usuário logado, se quiser disponível
        return include __DIR__ . "/../views/{$path}.php";
    }
}