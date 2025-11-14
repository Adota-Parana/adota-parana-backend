<?php

namespace App\Models;

use Core\Database\ActiveRecord\Model;

class Specie extends Model
{
    protected static string $table = 'species';
    protected static array $columns = ['name'];
}