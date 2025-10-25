<?php

namespace App\Controllers;

use Core\Http\Request;
use Core\Http\Controllers\Controller;
use App\Models\Pet;
use App\Services\Auth;
use Lib\FlashMessage;

class PetController extends Controller
{
    public function index()
    {
        $pets = Pet::all();
        return $this->render('home/feed', ['pets' => $pets]);
    }

    public function create()
    {
        return $this->render('/pets/create');
    }

    public function store(\Core\Http\Request $request)
    {
        $user = Auth::user();

        if(!$user){
            FlashMessage::danger('Você precisa estar logado para criar um pet!');
            return redirect('/login');
        }


        $pet = new Pet($_POST);
        $pet->user_id = $user -> id;
        $pet->post_date = date('Y-m-d H:i:s');
        $pet->status = 'disponivel';

        if($pet->save()){
            FlashMessage::success('Pet cadastrado com sucesso!');
            return redirect('/feed');
        }
        else{
            FlashMessage::danger('Erro ao cadastrar pet!');
            return $this->render('/pets/create');
        }
    }

    public function edit(int $id)
    {
        $pet = Pet::find($id);
        $user = Auth::user();

        if(!$user || $user->id !== $pet->user_id){
            FlashMessage::danger('Você não tem permissão para editar este pet!');
            return redirect('/feed');
        }

        return $this->render('/pets/edit', ['pet' => $pet]);
    }

    public function update(\Core\Http\Request $request, int $id)
    {
        $pet = Pet::find($id);
        $user = Auth::user();

        if(!$user || $user->id !== $pet->user_id){
            FlashMessage::danger('Você não tem permissão para editar este pet!');
            return redirect('/feed');
        }

        $pet->fill($_POST);
        
        if($pet->save()){
            FlashMessage::success('Pet atualizado com sucesso!');
            return redirect('/feed');
        }
        else{
            FlashMessage::danger('Erro ao atualizar pet!');
            return $this->render('/pets/edit', ['pet' => $pet]);
        }
    }

    public function destroy(int $id)
    {
        $pet = Pet::find($id);
        $user = Auth::user();

        if(!$user || $user->id !== $pet->user_id){
            FlashMessage::danger('Você não tem permissão para excluir este pet!');
            return redirect('/feed');
        }

        if($pet->delete()){
            FlashMessage::success('Pet excluído com sucesso!');
            return redirect('/feed');
        }
        else{
            FlashMessage::danger('Erro ao excluir pet!');
            return redirect('/feed');
        }
    }
}