<?php

namespace Database\Populate;

use App\Models\Species;

class SpeciesPopulate
{
    public static function populate()
    {
        // Função auxiliar para criar uma espécie
        $createSpecies = function ($data) {
            $species = new Species();
            $species->name = $data['name'];
            $species->save();
        };

        // Lista de espécies padrão
        $speciesList = [
            ['name' => 'Cachorro'],
            ['name' => 'Gato'],
            ['name' => 'Coelho'],
            ['name' => 'Pássaro'],
            ['name' => 'Peixe']
        ];

        foreach ($speciesList as $data) {
            $createSpecies($data);
        }

        echo "Species populated with " . count($speciesList) . " records\n";
    }
}
