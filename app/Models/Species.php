<?php

namespace App\Models;

use Core\Database\ActiveRecord\Model;
use Core\Http\Controllers\Controller;

class Species extends Model
{
    // Nome da tabela no banco
    protected static string $table = 'species';

    // Colunas da tabela
    protected static array $columns = [
        'id',
        'name',
    ];

    // Colunas que podem ser preenchidas via formulário (mass assignment)
    protected static array $fillable = [
        'name',
    ];
}
