<?php

namespace App\Models;

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

    public function validates(): void
    {
        Validations::notEmpty('name', $this);
        Validations::notEmpty('specie_id', $this);
        Validations::notEmpty('user_id', $this);
        Validations::notEmpty('status', $this);
        Validations::notEmpty('post_date', $this);
    }
}
