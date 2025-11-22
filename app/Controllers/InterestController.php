<?php

namespace App\Controllers;

use App\Models\Interest;
use App\Models\Pet;
use App\Services\Auth;
use Core\Http\Request;
use Lib\FlashMessage;
use Core\Http\Controllers\Controller;

class InterestController extends Controller
{
    public function store(Request $request)
    {
        $user = Auth::user();
        if (!$user) {
            FlashMessage::danger('Você precisa estar logado para demonstrar interesse!');
            header('Location: /login');
            return;
        }

        $pet_id = (int) $request->getParam('pet_id');
        $pet = Pet::findById($pet_id);

        if (!$pet) {
            FlashMessage::danger('Pet não encontrado!');
            header('Location: /feed');
            return;
        }

        if ($pet->user_id == $user->id) {
            FlashMessage::warning('Você não pode demonstrar interesse no seu próprio pet.');
            header('Location: /pets/' . $pet_id);
            return;
        }

        $existingInterest = Interest::where(['user_id' => $user->id, 'pet_id' => $pet_id]);
        if ($existingInterest) {
            FlashMessage::info('Você já demonstrou interesse neste pet.');
            header('Location: /pets/' . $pet_id);
            return;
        }

        $interest = new Interest([
            'user_id' => $user->id,
            'pet_id' => $pet_id,
            'status' => 'pending',
            'interested_date' => date('Y-m-d H:i:s')
        ]);

        if ($interest->save()) {
            FlashMessage::success('Interesse registrado com sucesso! O doador será notificado.');
        } else {
            FlashMessage::danger('Ocorreu um erro ao registrar seu interesse. Tente novamente.');
        }

        header('Location: /pets/show/' . $pet_id);
    }

    public function index()
    {
        $user = Auth::user();
        if (!$user) {
            FlashMessage::danger('Você precisa estar logado para ver seus interesses.');
            header('Location: /login');
            return;
        }

        // Interests in user's pets
        $myPets = Pet::where(['user_id' => $user->id]);
        $myPetIds = array_map(fn($pet) => $pet->id, $myPets);

        $receivedInterests = [];
        if (!empty($myPetIds)) {
            $receivedInterests = Interest::whereIn('pet_id', $myPetIds);
            foreach ($receivedInterests as $interest) {
                $interest->user = $interest->user();
                $interest->pet = $interest->pet();
            }
        }


        // User's interests in other pets
        $myInterests = Interest::where(['user_id' => $user->id]);
        foreach ($myInterests as $interest) {
            $interest->pet = $interest->pet();
            $petOwner = $interest->pet->user();
            if ($interest->status === 'approved') {
                $interest->pet_owner_email = $petOwner->email;
            }
        }

        $this->render('user/interests', [
            'receivedInterests' => $receivedInterests,
            'myInterests' => $myInterests,
        ]);
    }

    public function update(Request $request)
    {
        $user = Auth::user();
        if (!$user) {
            FlashMessage::danger('Acesso não autorizado.');
            header('Location: /login');
            return;
        }

        $user_id = (int) $request->getParam('user_id');
        $pet_id = (int) $request->getParam('pet_id');
        $status = $request->getParam('status');

        $interest = Interest::findBy(['user_id' => $user_id, 'pet_id' => $pet_id]);

        if (!$interest) {
            FlashMessage::danger('Interesse não encontrado.');
            header('Location: /user/interests');
            return;
        }

        $pet = $interest->pet();

        if ($pet->user_id !== $user->id) {
            FlashMessage::danger('Você não tem permissão para alterar este interesse.');
            header('Location: /user/interests');
            return;
        }

        if (in_array($status, ['approved', 'rejected'])) {
            $interest->status = $status;
            if ($interest->save()) {
                if ($status === 'approved') {
                    // Mark pet as unavailable
                    $pet->status = 'adotado';
                    $pet->save();
                }
                FlashMessage::success('Status do interesse atualizado com sucesso.');
            } else {
                FlashMessage::danger('Erro ao atualizar o status do interesse.');
            }
        } else {
            FlashMessage::warning('Status inválido.');
        }

        header('Location: /user/interests');
    }
}
