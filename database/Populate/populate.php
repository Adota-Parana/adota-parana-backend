<?php

require __DIR__ . '/../../config/bootstrap.php';

use Core\Database\Database;
use Database\Populate\SpeciesPopulate;
use Database\Populate\UsersPopulate;

Database::migrate();
UsersPopulate::populate();
SpeciesPopulate::populate();