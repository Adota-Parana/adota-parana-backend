<?php

namespace App\Services;

use App\Models\PetImage;
use Lib\FlashMessage;

class PetImageService
{
    private const ALLOWED_EXTENSIONS = ['jpg', 'jpeg', 'png', 'gif'];

    public static function saveImages(array $files, int $petId): bool
    {
        $uploadDir = realpath(__DIR__ . '/../../public') . '/assets/uploads/pets/';

        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0777, true);
        }

        $successCount = 0;
        $numFiles = count($files['name']);

        for ($i = 0; $i < $numFiles; $i++) {

            if ($files['error'][$i] !== UPLOAD_ERR_OK) {
                continue;
            }

            $fileName = $files['name'][$i];
            $tmpName = $files['tmp_name'][$i];
            $extension = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));

            if (!in_array($extension, self::ALLOWED_EXTENSIONS)) {
                FlashMessage::danger("Arquivo '$fileName' não é permitido. Use jpg, jpeg, png, ou gif.");
                continue;
            }

            // Nome único para evitar sobrescrita
            $newName = uniqid("pet_{$petId}_", true) . "." . $extension;
            $destination = $uploadDir . '/' . $newName;

            if (!move_uploaded_file($tmpName, $destination)) {
                FlashMessage::danger("Não foi possível mover o arquivo '$fileName'.");
                continue;
            }

            $petImage = new PetImage([
                'pet_id' => $petId,
                'image_path' => "assets/uploads/pets/" . $newName
            ]);

            if ($petImage->save()) {
                $successCount++;
            }
        }

        return $successCount > 0;
    }
}
