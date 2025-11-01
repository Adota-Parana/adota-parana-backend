<?php

namespace App\Services;

use App\Models\PetImage;
use Lib\FlashMessage;

class PetImageService
{
    private const ALLOWED_EXTENSIONS = ['jpg', 'jpeg', 'png', 'gif'];

    /**
     * Salva múltiplas imagens para um pet.
     *
     * @param array $files O array de arquivos do $_FILES (ex: $_FILES['pet_images']).
     * @param int $petId O ID do pet.
     * @return bool Retorna true se pelo menos uma imagem for salva com sucesso.
     */
    public static function saveImages(array $files, int $petId): bool
    {
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
                FlashMessage::warning("Extensão '{$extension}' não permitida para o arquivo '{$fileName}'. Use jpg, jpeg, png, ou gif.");
                continue;
            }

            $imageContent = file_get_contents($tmpName);

            $petImage = new PetImage([
                'pet_id' => $petId,
                'image' => $imageContent
            ]);

            if ($petImage->save()) {
                $successCount++;
            } else {
                FlashMessage::danger("Não foi possível salvar a imagem '{$fileName}' no banco de dados.");
            }
        }

        return $successCount > 0;
    }
}
