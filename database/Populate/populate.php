<?php

require __DIR__ . '/../../config/bootstrap.php';

use Core\Database\Database;
use Database\Populate\UsersPopulate;
use Database\Populate\SpeciesPopulate;
use Database\Populate\PetsPopulate;


Database::migrate();
UsersPopulate::populate();
SpeciesPopulate::populate();
PetsPopulate::populate();
