<?php

namespace App\Models;

use App\Services\PetImageService;
use Lib\Validations;
use Core\Database\ActiveRecord\Model;

class Pet extends Model
{
    protected static string $table = 'pets';
    protected static array $columns = [
        'specie_id',
        'user_id',
        'name',
        'birth_date',
        'sex',
        'is_vaccinated',
        'is_neutered',
        'description',
        'status',
        'post_date',
    ];

    public array $images = [];

    public function images()
    {
        return $this->hasMany(PetImage::class, 'pet_id');
    }

    public function validates(): void
    {
        Validations::notEmpty('name', $this);
        Validations::notEmpty('specie_id', $this);
        Validations::notEmpty('user_id', $this);
        Validations::notEmpty('status', $this);
        Validations::notEmpty('post_date', $this);
        Validations::notEmpty('sex', $this);
        Validations::notEmpty('birth_date', $this);
    }

    public function petsImage(): PetImageService
    {
        return new PetImageService($this);
    }
}
