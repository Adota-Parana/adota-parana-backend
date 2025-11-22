<?php

namespace App\Models;

use Core\Database\Database;
use Core\Database\ActiveRecord\Model;
use PDO;

class Interest extends Model
{
    protected static string $table = 'interesting';
    protected static array $columns = [
        'user_id',
        'pet_id',
        'status',
        'interested_date',
        'message'
    ];

    // Sobrescreve o método newRecord para verificar a chave primária composta
    public function newRecord(): bool
    {
        return !isset($this->attributes['user_id']) || !isset($this->attributes['pet_id']);
    }

    // Sobrescreve o método where para não selecionar a coluna 'id'
    public static function where(array $conditions): array
    {
        $table = static::$table;
        $attributes = implode(', ', static::$columns);

        $sql = "SELECT {$attributes} FROM {$table} WHERE ";

        $sqlConditions = array_map(fn($column) => "{$column} = :{$column}", array_keys($conditions));
        $sql .= implode(' AND ', $sqlConditions);

        $pdo = Database::getDatabaseConn();
        $stmt = $pdo->prepare($sql);

        foreach ($conditions as $column => $value) {
            $stmt->bindValue($column, $value);
        }

        $stmt->execute();
        $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $models = [];
        foreach ($rows as $row) {
            $models[] = new static($row);
        }
        return $models;
    }

    // Sobrescreve o método save para lidar com a chave primária composta
    public function save(): bool
    {
        if (!$this->isValid()) {
            return false;
        }

        $pdo = Database::getDatabaseConn();
        $table = static::$table;

        if ($this->newRecord()) {
            // Lógica de inserção
            $attributes = implode(', ', static::$columns);
            $values = ':' . implode(', :', static::$columns);
            $sql = "INSERT INTO {$table} ({$attributes}) VALUES ({$values});";
            $stmt = $pdo->prepare($sql);
        } else {
            // Lógica de atualização
            $sets = array_map(fn($col) => "{$col} = :{$col}", static::$columns);
            $sql = "UPDATE {$table} SET " . implode(', ', $sets) . " WHERE user_id = :user_id AND pet_id = :pet_id";
            $stmt = $pdo->prepare($sql);
        }

        foreach (static::$columns as $column) {
            $stmt->bindValue($column, $this->$column);
        }

        return $stmt->execute();
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function pet()
    {
        return $this->belongsTo(Pet::class, 'pet_id');
    }
}
