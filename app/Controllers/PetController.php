<?php

namespace App\Controllers;

use App\Models\Pet;
use App\Services\Auth;
use Core\Http\Request;
use Lib\FlashMessage;

class PetController
{
    public function index()
    {
        $pets = Pet::all();
        $this->view('home/feed', ['pets' => $pets]);
    }

    public function create()
    {
        $this->view('pets/create');
    }

    public function store(Request $request)
    {
        $user = Auth::user();

        if (!$user) {
            FlashMessage::danger('Você precisa estar logado para criar um pet!');
            header('Location: /login');
            return;
        }

        $pet = Pet::all();
        $pet->user_id = $user->id;
        $pet->post_date = date('Y-m-d H:i:s');
        $pet->status = 'disponivel';

        if ($pet->save()) {
            FlashMessage::success('Pet cadastrado com sucesso!');
            header('Location: /feed');
            return;
        } else {
            FlashMessage::danger('Erro ao cadastrar pet!');
            $this->view('pets/create');
            return;
        }
    }

    public function edit(int $id)
    {
        $pet = Pet::find($id);
        $user = Auth::user();

        if (!$user || $user->id !== $pet->user_id) {
            FlashMessage::danger('Você não tem permissão para editar este pet!');
            header('Location: /feed');
            return;
        }

        $this->view('pets/edit', ['pet' => $pet]);
    }

    public function update(Request $request, int $id)
    {
        $pet = Pet::find($id);
        $user = Auth::user();

        if (!$user || $user->id !== $pet->user_id) {
            FlashMessage::danger('Você não tem permissão para editar este pet!');
            header('Location: /feed');
            return;
        }

        $pet->fill($request->all());

        if ($pet->save()) {
            FlashMessage::success('Pet atualizado com sucesso!');
            header('Location: /feed');
            return;
        } else {
            FlashMessage::danger('Erro ao atualizar pet!');
            $this->view('pets/edit', ['pet' => $pet]);
            return;
        }
    }

    public function destroy(int $id)
    {
        $pet = Pet::find($id);
        $user = Auth::user();

        if (!$user || $user->id !== $pet->user_id) {
            FlashMessage::danger('Você não tem permissão para excluir este pet!');
            header('Location: /feed');
            return;
        }

        if ($pet->delete()) {
            FlashMessage::success('Pet excluído com sucesso!');
            header('Location: /feed');
            return;
        } else {
            FlashMessage::danger('Erro ao excluir pet!');
            header('Location: /feed');
            return;
        }
    }

    protected function view(string $viewName, array $data = []): void
    {
        $view = __DIR__ . '/../views/' . $viewName . '.phtml';
        extract($data);
        require __DIR__ . '/../views/layouts/application.phtml';
    }
}
