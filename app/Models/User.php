<?php

namespace App\Models;

use Lib\Validations;
use Core\Database\ActiveRecord\Model;

/**
 * @property int $id
 * @property string $name
 * @property string $email
 * @property string $encrypted_password
 * @property string $phone
 * @property string $role
 */

class User extends Model
{
    protected static string $table = 'users';
    protected static array $columns = ['name', 'email', 'encrypted_password', 'phone', 'role'];
    protected ?string $password = null;
    protected ?string $password_confirmation = null;

    public function validates(): void
    {
        Validations::notEmpty('name', $this);
        Validations::notEmpty('email', $this);
        Validations::notEmpty('phone', $this);

        Validations::uniqueness('email', $this);
        Validations::uniqueness('phone', $this);

        Validations::email('email', $this);
        Validations::phone('phone', $this);

        if ($this->newRecord()) {
            Validations::notEmpty('encrypted_password', $this);
            Validations::passwordConfirmation($this);
        }
    }

    public function beforeSave(): void
    {
        if (!empty($this->password)) {
            $this->encrypted_password = password_hash($this->password, PASSWORD_BCRYPT);
        }
    }

    public function authenticate(string $password): bool
    {
        if ($this->encrypted_password === null) {
            return false;
        }

        $hash = $this->encrypted_password;

        if (preg_match('/^\$2[aby]\$/', $hash) || str_starts_with($hash, '$argon2')) {
            return password_verify($password, $hash);
        }

        if (preg_match('/^[0-9a-f]{64}$/i', $hash)) {
            return hash('sha256', $password) === $hash;
        }

        return false;
    }

    public static function findByEmail(string $email): User | null
    {
        return User::findBy(['email' => $email]);
    }

    public static function findByPhone(int $phone): User | null
    {
        return User::findBy(['phone' => $phone]);
    }

        public static function findById(int $id): ?static // Isso aqui ta errado, tem que pegar o findById do Model*
    {
        return User::findBy(['id' => $id]);
    }

    public function isAdmin(): bool
    {
        return $this->role === 'admin';
    }

    public function __set(string $property, mixed $value): void
    {
        parent::__set($property, $value);

        if (
            $property === 'password' &&
            $this->newRecord() &&
            $value !== null && $value !== ''
        ) {
            $this->encrypted_password = password_hash($value, PASSWORD_DEFAULT);
        }
    }
}