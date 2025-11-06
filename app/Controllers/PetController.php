<?php

namespace App\Controllers;

use App\Models\Pet;
use App\Models\Species;
use App\Services\Auth;
use Core\Http\Controllers\Controller;
use Core\Http\Request;
use Lib\FlashMessage;

class PetController extends Controller
{
    public function index()
    {
        $pets = Pet::all();
        $this->render('/home/feed', ['pets' => $pets]);
    }

    public function create()
    {
         $speciesList = Species::all(); // pega todas as espécies do banco
         return $this->render('/pets/create', [
        'speciesList' => $speciesList
    ]);
    }

   public function store(Request $request)
    {
        $user = Auth::user();

        if (!$user) {
            FlashMessage::danger('Você precisa estar logado para criar um pet!');
            return $this->redirectTo('/login');
        }

        $pet = new Pet();

        $pet->specie_id    = $request->post('specie_id');
        $pet->name         = trim($request->post('name'));
        $pet->birth_date   = $request->post('birth_date');
        $pet->sex          = $request->post('sex');
        $pet->is_neutered   = $request->post('is_neutered') ? 1 : 0;
        $pet->is_vaccinated = $request->post('is_vaccinated') ? 1 : 0;
        $pet->description  = trim($request->post('description'));
        $pet->status       = 'disponivel';
        $pet->user_id      = $user->id;
        $pet->post_date    = date('Y-m-d H:i:s');

        if (!$pet->isValid()) {
            $speciesList = Species::all();
            return $this->render('/pets/create', [
                'pet' => $pet,
                'errors' => $pet->errors,
                'speciesList' => $speciesList
            ]);
        }   

        if ($pet->save()) {
            FlashMessage::success('Pet cadastrado com sucesso!');
            return $this->redirectTo('/home/feed');
            exit;
        } else {
            FlashMessage::danger('Erro ao cadastrar pet!');
            return $this->render('/pets/create');
            exit;
        }
    }


    public function edit(Request $request)
    {
        // Melhorar isso!!
        $uri = $_SERVER['REQUEST_URI']; 
        $segments = explode('/', trim($uri, '/'));
        $id = (int) end($segments);

        $pet = Pet::findById($id);
        $user = Auth::user();

        if(!$user || $user->id !== $pet->user_id){
            FlashMessage::danger('Você não tem permissão para editar este pet!');
            $this->redirectTo('/home/feed');
        }

        // Carregar todas as espécies
        $speciesList = Species::all();

        // Passar para a view
        $this->render('/pets/edit', [
            'pet' => $pet,
            'speciesList' => $speciesList
        ]);
    }

    public function update(Request $request)
    {
        // Extrai o ID da URL
        $uri = $_SERVER['REQUEST_URI']; // ex: /pets/update/5
        $segments = explode('/', trim($uri, '/'));

        // Considerando URL no formato /pets/update/{id}
        $id = (int) end($segments); // pega o último segmento

        $pet = Pet::findById($id);
        $user = Auth::user();

        if (!$pet || !$user || $user->id !== $pet->user_id) {
            FlashMessage::danger('Você não tem permissão para editar este pet!');
            return $this->redirectTo('/home/feed');
        }

      // Atualizando campo a campo
        $pet->specie_id    = $request->post('specie_id');
        $pet->name         = trim($request->post('name'));
        $pet->birth_date   = $request->post('birth_date');
        $pet->sex          = $request->post('sex');
        $pet->is_neutered   = $request->post('is_neutered') ? 1 : 0;
        $pet->is_vaccinated = $request->post('is_vaccinated') ? 1 : 0;
        $pet->description  = trim($request->post('description'));
        $pet->status       = $request->post('status');

        if (!$pet->isValid()) {
            $speciesList = Species::all();
            return $this->render('/pets/edit', [
                'pet' => $pet,
                'errors' => $pet->errors,
                'speciesList' => $speciesList
            ]);
        }   

        if ($pet->save()) {
            FlashMessage::success('Pet atualizado com sucesso!');
            return $this->redirectTo('/home/feed');
        } else {
            FlashMessage::danger('Erro ao atualizar pet!');
            return $this->render('/pets/edit', ['pet' => $pet]);
        }
    }

    public function destroy(Request $request)
    {
        // Extrai o ID da URL
        $uri = $_SERVER['REQUEST_URI']; // ex: /pets/update/5
        $segments = explode('/', trim($uri, '/'));

        // Considerando URL no formato /pets/update/{id}
        $id = (int) end($segments); // pega o último segmento


        $pet = Pet::findById($id);
        $user = Auth::user();

        if(!$user || $user->id !== $pet->user_id){
            FlashMessage::danger('Você não tem permissão para excluir este pet!');
            $this->redirectTo('/home/feed');
        }

        if($pet->destroy()){
            FlashMessage::success('Pet excluído com sucesso!');
            $this->redirectTo('/home/feed');
        }
        else{
            FlashMessage::danger('Erro ao excluir pet!');
            $this->redirectTo('/home/feed');
        }
    }
}