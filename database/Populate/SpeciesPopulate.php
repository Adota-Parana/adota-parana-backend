<?php

namespace Database\Populate;

use App\Models\Specie;
use PDOException;

class SpeciesPopulate
{
    public static function populate()
    {
        $species = [
            ['name' => 'Cachorro'],
            ['name' => 'Gato'],
            ['name' => 'Pássaro'],
            ['name' => 'Peixe'],
            ['name' => 'Roedor'],
        ];

        foreach ($species as $specieData) {
            try {
                $specie = new Specie();
                $specie->name = $specieData['name'];
                $specie->save();
            } catch (PDOException $e) {
                if ($e->getCode() !== '23000') {
                    throw $e;
                }
            }
        }
    }
}
