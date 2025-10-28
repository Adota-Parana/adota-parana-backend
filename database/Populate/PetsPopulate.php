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

        $pets = [
            [
                'name' => 'Rex',
                'description' => 'Um cão amigável e brincalhão.',
                'age' => 3,
                'adopted' => false,
            ],
            [
                'name' => 'Miau',
                'description' => 'Um gato curioso e independente.',
                'age' => 2,
                'adopted' => false,
            ],
            [
                'name' => 'Piu',
                'description' => 'Um pássaro cantor e alegre.',
                'age' => 1,
                'adopted' => true,
            ],
            [
                'name' => 'Nemo',
                'description' => 'Um peixe colorido e tranquilo.',
                'age' => 1,
                'adopted' => false,
            ],
            [
                'name' => 'Bolinha',
                'description' => 'Um hamster fofo e ativo.',
                'age' => 1,
                'adopted' => false,
            ],
        ];

        foreach ($pets as $petData) {
            try {
                $pet = new Pet();
                $pet->name = $petData['name'];
                $pet->description = $petData['description'];
                $pet->birth_date = date('Y-m-d', strtotime("-{$petData['age']} years"));
                $pet->status = $petData['adopted'] ? 'ADOPTED' : 'AVAILABLE';
                $pet->specie_id = $species[array_rand($species)]->id;
                $pet->user_id = $users[array_rand($users)]->id;
                $pet->sex = ['M', 'F'][array_rand(['M', 'F'])];
                $pet->is_vaccinated = random_int(0, 1);
                $pet->is_neutered = random_int(0, 1);
                $pet->post_date = date('Y-m-d H:i:s');
                $pet->save();
            } catch (PDOException $e) {
                if ($e->getCode() !== '23000') {
                    throw $e;
                }
            }
        }
    }
}
