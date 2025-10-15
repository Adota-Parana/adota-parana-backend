<?php

namespace App\Models;

use Lib\Validations;
use Core\Database\ActiveRecord\Model;

/**
 * @property int $id
 * @property int $specie_id
 * @property int $user_id
 * @property string $name
 * @property date $birth_date
 * @property char $sex
 * @property string $description
 * @property string $img_path
 * @property string $status
 * @property datetime $post_date
*/


class Pet extends Model
{
    protected static string $table = 'pets';
    protected static array $columns = ['name', 'birth_date', 'sex', 'description', 'img_path', 'status', 'post_date'];

    public function validation(): void
    {
        Validations::notEmpty('name', $this);
        Validations::notEmpty('status', $this);
        Validations::notEmpty('post_date', $this);

    }

    


}
