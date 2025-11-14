<?php

namespace App\Services;

use App\Models\PetImage;
use App\Validators\ImageValidator;
use Lib\FlashMessage;

class PetImageService
{
    public static function saveImages(array $files, int $petId): bool
    {
        $uploadDir = realpath(__DIR__ . '/../../public') . '/assets/uploads/pets/';

        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0777, true);
        }

        $successCount = 0;
        $numFiles = count($files['name']);

        for ($i = 0; $i < $numFiles; $i++) {

            $file = [
                'name' => $files['name'][$i],
                'tmp_name' => $files['tmp_name'][$i],
                'error' => $files['error'][$i],
                'size' => $files['size'][$i],
            ];

            // ✅ Validação usando o Fluent Validator
            $errors = ImageValidator::file($file)
                ->image()         
                ->max(2)                          
                ->mimes(['jpg', 'jpeg', 'png', 'gif']) 
                ->validate();                      

            if (!empty($errors)) {
                foreach ($errors as $error) {
                    FlashMessage::danger($error);
                }
                continue; 
            }

            // ✅ Pega a extensão
            $extension = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));

            // ✅ Gera nome único
            $newName = uniqid("pet_{$petId}_", true) . "." . $extension;
            $destination = $uploadDir . '/' . $newName;

            // ✅ Move o arquivo para a pasta pública
            if (!move_uploaded_file($file['tmp_name'], $destination)) {
                FlashMessage::danger("Não foi possível salvar o arquivo '{$file['name']}'.");
                continue;
            }

            // ✅ Salvar no banco
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

    public static function validateImages(array $files): array
{
    $errors = [];
    $numFiles = count($files['name']);

    for ($i = 0; $i < $numFiles; $i++) {
        $file = [
            'name' => $files['name'][$i],
            'tmp_name' => $files['tmp_name'][$i],
            'size' => $files['size'][$i],
            'error' => $files['error'][$i]
        ];

        $err = \App\Validators\ImageValidator::file($file)
            ->image()
            ->max(2) // 2MB
            ->mimes(['jpg', 'jpeg', 'png', 'gif'])
            ->validate();

        if (!empty($err)) {
            $errors[] = "{$file['name']}: " . implode(", ", $err);
        }
    }

    return $errors;
}
}
