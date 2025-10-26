<?php

namespace App\Controllers;

use App\Models\Specie;
use App\Models\Pet;
use App\Services\Auth;
use Core\Http\Request;
use Lib\FlashMessage;

class PetController
{
    public function index()
    {
        $pets = Pet::all();
        $currentUser = Auth::user();
        $this->view('home/feed', [
            'pets' => $pets,
            'currentUser' => $currentUser
        ]);
    }

    public function create()
    {
        $species = Specie::all();
        $this->view('pets/create', [
            'species' => $species,
            'pet' => new Pet(),
            'errors' => []
        ]);
    }

    public function store(Request $request): void
    {
        $user = Auth::user();

        if (!$user) {
            FlashMessage::danger('Você precisa estar logado para criar um pet!');
            header('Location: /login');
            return;
        }

        $pet = new Pet();
        $data = $_POST;

        $pet->name = $data['name'] ?? null;
        $pet->specie_id = $data['specie_id'] ?? null;
        $pet->birth_date = empty($data['birth_date']) ? null : $data['birth_date'];
        $pet->sex = $data['sex'] ?? null;
        $pet->is_vaccinated = $data['is_vaccinated'] ?? 0;
        $pet->is_neutered = $data['is_neutered'] ?? 0;
        $pet->description = $data['description'] ?? null;
        $pet->user_id = $user->id;
        $pet->post_date = date('Y-m-d H:i:s');
        $pet->status = 'disponível';

        if ($pet->save()) {
            FlashMessage::success('Pet cadastrado com sucesso!');
            header('Location: /feed');
            return;
        } else {
            FlashMessage::danger('Erro ao cadastrar pet! Verifique os dados.');
            $this->view('pets/create', [
                'pet' => $pet,
                'errors' => $pet->errors,
                'species' => Specie::all()
            ]);
        }
    }

    public function edit(Request $request): void
    {
        $id = (int) $request->getParam('id');
        $pet = Pet::findById($id);
        $user = Auth::user();

        if (!$this->isOwner($user, $pet)) {
            FlashMessage::danger('Você não tem permissão para editar este pet!');
            header('Location: /feed');
            return;
        }

        $this->view('pets/edit', ['pet' => $pet, 'species' => Specie::all()]);
    }

    public function update(Request $request): void
    {
        $id = (int) $request->getParam('id');
        $pet = Pet::findById($id);
        $user = Auth::user();

        if (!$this->isOwner($user, $pet)) {
            FlashMessage::danger('Você não tem permissão para editar este pet!');
            header('Location: /feed');
            return;
        }

        $data = $_POST;

        $pet->name = $data['name'] ?? $pet->name;
        $pet->specie_id = $data['specie_id'] ?? $pet->specie_id;
        $pet->birth_date = empty($data['birth_date']) ? $pet->birth_date : $data['birth_date'];
        $pet->sex = $data['sex'] ?? $pet->sex;
        $pet->is_vaccinated = $data['is_vaccinated'] ?? 0;
        $pet->is_neutered = $data['is_neutered'] ?? 0;
        $pet->description = $data['description'] ?? $pet->description;

        if ($pet->save()) {
            FlashMessage::success('Pet atualizado com sucesso!');
            header('Location: /feed');
            return;
        }
        $this->view('pets/edit', [
            'pet' => $pet,
            'errors' => $pet->errors,
            'species' => Specie::all()
        ]);
    }
    private function isOwner(?object $user, ?object $pet): bool
    {
        if (!$user || !$pet) {
            return false;
        }

        if ($user->role == 'admin') {
            return true;
        }

        return $user->id === $pet->user_id;
    }

    public function destroy(Request $request): void
    {
        $id = (int) $request->getParam('id');
        $pet = Pet::findById($id);
        $user = Auth::user();

        if (!$this->isOwner($user, $pet)) {
            FlashMessage::danger('Você não tem permissão para excluir este pet!');
            header('Location: /feed');
            return;
        }

        if ($pet->destroy()) {
            FlashMessage::success('Pet excluído com sucesso!');
        } 
        else {
            FlashMessage::danger('Erro ao excluir pet!');
        }

        header('Location: /feed');
    }

    protected function view(string $viewName, array $data = []): void
    {
        $view = __DIR__ . '/../views/' . $viewName . '.phtml';
        extract($data);
        require __DIR__ . '/../views/layouts/application.phtml';
    }

}
