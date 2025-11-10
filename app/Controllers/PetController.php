<?php

namespace App\Controllers;

use App\Models\Specie;
use App\Models\Pet;
use App\Models\PetImage;
use App\Services\Auth;
use App\Services\PetImageService;
use Core\Http\Request;
use Lib\FlashMessage;
use Core\Http\Controllers\Controller;

class PetController extends Controller
{
    public function index()
    {
        $pets = Pet::all();
        foreach ($pets as $pet) {
            $pet->images = $pet->images()->get();
        }

        $currentUser = Auth::user();
        $this->render('home/feed', [
            'pets' => $pets,
            'currentUser' => $currentUser
        ]);
    }

    public function show(Request $request): void
    {
        $id = (int) $request->getParam('id');
        $pet = Pet::findById($id);

        if (!$pet) {
            FlashMessage::danger('Pet não encontrado!');
            header('Location: /feed');
            return;
        }

        $pet->images = $pet->images()->get();

        $this->render('pets/show', ['pet' => $pet]);
    }

    public function create()
    {
        $species = Specie::all();
        $this->render('pets/create', [
            'species' => $species,
            'pet' => new Pet(),
            'errors' => []
        ]);
    }

    public function store(Request $request)
    {
        $user = Auth::user();

        if (!$user) {
            FlashMessage::danger('Você precisa estar logado para criar um pet!');
            header('Location: /login');
            return;
        }

        $pet = new Pet($_POST);
        $pet->user_id = $user->id;
        $pet->post_date = date('Y-m-d H:i:s');
        $pet->status = 'disponível';

        // ✅ Valida dados do Pet (nome, espécie, etc)
        if (!$pet->isValid()) {
            return $this->render('pets/create', [
                'pet' => $pet,
                'errors' => $pet->errors,
                'species' => Specie::all()
            ]);
        }

        // ✅ Se houver imagens, valida antes de salvar o Pet
        if (isset($_FILES['pet_images']) && !empty($_FILES['pet_images']['name'][0])) {
            $imageErrors = PetImageService::validateImages($_FILES['pet_images']);

            if (!empty($imageErrors)) {
                FlashMessage::danger(implode("<br>", $imageErrors));

                return $this->render('pets/create', [
                    'pet' => $pet,
                    'errors' => $pet->errors,
                    'species' => Specie::all()
                ]);
            }
        }

        // ✅ Agora sim salva o pet
        if ($pet->save()) {

            // ✅ Agora salva as imagens, pois já passou na validação
            if (!empty($_FILES['pet_images']['name'][0])) {
                PetImageService::saveImages($_FILES['pet_images'], $pet->id);
            }

            FlashMessage::success('Pet cadastrado com sucesso!');
            header('Location: /feed');
            return;
        }

        FlashMessage::danger('Erro ao cadastrar pet!');
        $this->render('pets/create', [
            'pet' => $pet,
            'errors' => $pet->errors,
            'species' => Specie::all()
        ]);
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

        $pet->images = $pet->images()->get();

        $this->render('pets/edit', ['pet' => $pet, 'species' => Specie::all()]);
    }

    public function update(Request $request)
    {
        $id = (int) $request->getParam('id');
        $pet = Pet::findById($id);
        $user = Auth::user();

        if (!$this->isOwner($user, $pet)) {
            FlashMessage::danger('Você não tem permissão para editar este pet!');
            header('Location: /feed');
            return;
      }   

        if (!empty($_FILES['pet_images']['name'][0])) {

            $imageErrors = \App\Services\PetImageService::validateImages($_FILES['pet_images']);

            if (!empty($imageErrors)) {
                FlashMessage::danger(implode("<br>", $imageErrors));

                $pet->images = $pet->images()->get();

                return $this->render('pets/edit', [
                    'pet' => $pet,
                    'species' => Specie::all()
                ]);
            }

            PetImageService::saveImages($_FILES['pet_images'], $pet->id);
            FlashMessage::success('Imagens adicionadas com sucesso!');
        }

        header('Location: /pets/' . $pet->id . '/edit');
        exit;
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

        $this->deleteImagesByPet($pet->id);

        if ($pet->destroy()) {
            FlashMessage::success('Pet excluído com sucesso!');
        } else {
            FlashMessage::danger('Erro ao excluir pet!');
        }

        header('Location: /feed');
    }

    public function destroyImage(Request $request): void
    {
        $imageId = (int) $request->getParam('id');
        $petImage = PetImage::findById($imageId);

        if (!$petImage) {
            FlashMessage::danger('Imagem não encontrada!');
            header('Location: /feed');
            return;
        }

        $pet = Pet::findById($petImage->pet_id);
        $user = Auth::user();

        if (!$this->isOwner($user, $pet)) {
            FlashMessage::danger('Você não tem permissão para excluir esta imagem!');
            header('Location: /feed');
            return;
        }

        // ✅ Caminho da imagem
        $filePath = __DIR__ . '/../../public/' . $petImage->image_path;

        if (file_exists($filePath)) {
            unlink($filePath);
        }

        // ✅ Remove do banco
        $petImage->destroy();

        FlashMessage::success('Imagem excluída com sucesso!');
        header('Location: /pets/' . $pet->id . '/edit');
    }

        public static function deleteImagesByPet(int $petId): void
    {
        $uploadDir = __DIR__ . '/../../public/';

        $images = PetImage::where(['pet_id' => $petId]);

        if (!$images) {
            return;
        }

        foreach ($images as $image) {
            $filePath = $uploadDir . $image->image_path;
            if (file_exists($filePath)) {
                unlink($filePath);
            }
        }
    }


    private function isOwner(?object $user, ?object $pet): bool
    {
        return $user && $pet && ($user->role === 'admin' || $user->id === $pet->user_id);
    }
}
