<?php

namespace Models;

use PDO;

class Tools
{
    protected $pdo;

    public function __construct(PDO $db)
    {
        $this->pdo = $db;
    }

    public function fetch(): array
    {
        $sql = 'SELECT * FROM tools ORDER BY id DESC;';
        $Sth = $this->pdo->prepare($sql);
        $Sth->execute();
        return $Sth->fetchAll(\PDO::FETCH_ASSOC);
    }

    public function available(): array
    {
        $sql = "SELECT id AS value, CONCAT (model, ' ', mark) AS  text FROM tools WHERE id NOT IN (SELECT tool_id FROM spot_tools) ";
        $Sth = $this->pdo->prepare($sql);
        $Sth->execute();
        return $Sth->fetchAll(\PDO::FETCH_ASSOC);
    }

}