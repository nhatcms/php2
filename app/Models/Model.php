<?php

namespace App\Models;

use App\Common\Database;

class Model
{
    protected $connection;
    protected $queryBuilder;
    public function __construct()
    {
        $this->connection = Database::getConnection();
        $this->queryBuilder = Database::getQueryBuilder();
    }
}
