<?php

namespace Database\Populate;

use App\Models\Pet;
use App\Models\Specie;
use App\Models\User;
use PDOException;

class PetsPopulate
{
    public static function populate()
    {
        $species = Specie::all();
        $users = User::all();

        if (count($species) === 0 || count($users) === 0) {
            echo "Cannot populate pets without species and users.\n";
            return;
        }

        $petNames = ['Max', 'Bella', 'Charlie', 'Lucy', 'Cooper', 'Daisy', 'Rocky', 'Luna', 'Milo', 'Sadie'];
        $descriptions = ['Amigável e brincalhão', 'Curioso e independente', 'Leal e protetor', 'Calmo e carinhoso', 'Energético e divertido'];

        for ($i = 0; $i < 50; $i++) {
            try {
                $pet = new Pet();
                $pet->name = $petNames[array_rand($petNames)];
                $pet->description = $descriptions[array_rand($descriptions)];
                $pet->birth_date = date('Y-m-d', strtotime("-" . rand(1, 10) . " years"));
                $pet->status = ['AVAILABLE', 'ADOPTED'][array_rand(['AVAILABLE', 'ADOPTED'])];
                $pet->specie_id = $species[array_rand($species)]->id;
                $pet->user_id = $users[array_rand($users)]->id;
                $pet->sex = ['M', 'F'][array_rand(['M', 'F'])];
                $pet->is_vaccinated = random_int(0, 1);
                $pet->is_neutered = random_int(0, 1);
                $pet->post_date = date('Y-m-d H:i:s');
                $pet->save();
            } catch (PDOException $e) {
                if ($e->getCode() !== '23000') { // 23000 is for integrity constraint violation
                    throw $e;
                }
            }
        }
    }
}
