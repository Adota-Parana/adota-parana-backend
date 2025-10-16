<?php

namespace App\Models;

use Core\Database\ActiveRecord\Model;

class PetImage extends Model
{
    protected static string $tableName = 'pet_images';
    protected static array $columns = ['pet_id', 'image'];
}
