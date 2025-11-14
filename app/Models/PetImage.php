<?php

namespace App\Models;

use Lib\Validations;
use Core\Database\ActiveRecord\Model;

class PetImage extends Model
{
    protected static string $table = 'pet_images';
    protected static array $columns = ['pet_id', 'image_path'];

    public function validates(): void
    {
        Validations::notEmpty('pet_id', $this);
        Validations::notEmpty('image_path', $this);
    }
}
