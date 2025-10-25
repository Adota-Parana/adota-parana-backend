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
        return view('/feed', ['pets' => $pets]);
    }

    public function create()
    {
        return view('/pets/create');
    }

    public function store(\Core\Http\Request $request)
    {
        $user = Auth::user();

        if(!$user){
            FlashMessage::danger('Você precisa estar logado para criar um pet!');
            return redirect('/login');
        }

        $pet = new Pet($request -> all());
        $pet->user_id = $user -> id;
        $pet->post_date = date('Y-m-d H:i:s');
        $pet->status = 'disponivel';

        if($pet->save()){
            FlashMessage::success('Pet cadastrado com sucesso!');
            return redirect('/feed');
        }
        else{
            FlashMessage::danger('Erro ao cadastrar pet!');
            return view('/pets/create');
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

        return view('/pets/edit', ['pet' => $pet]);
    }

    public function update(\Core\Http\Request $request, int $id)
    {
        $pet = Pet::find($id);
        $user = Auth::user();

        if(!$user || $user->id !== $pet->user_id){
            FlashMessage::danger('Você não tem permissão para editar este pet!');
            return redirect('/feed');
        }

        $pet->fill($request->all());

        if($pet->save()){
            FlashMessage::success('Pet atualizado com sucesso!');
            return redirect('/feed');
        }
        else{
            FLashMessage::danger('Erro ao atualizar pet!');
            return view('/pets/edit', ['pet' => $pet]);
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