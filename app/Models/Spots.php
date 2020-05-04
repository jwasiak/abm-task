<?php

namespace Models;

use PDO;

class Spots
{
    protected $pdo;

    public function __construct(PDO $db)
    {
        $this->pdo = $db;
    }


    public function fetch(): array
    {
        $sql = 'SELECT * FROM spots ORDER BY id DESC;';
        $Sth = $this->pdo->prepare($sql);
        $Sth->execute();
        return $Sth->fetchAll(\PDO::FETCH_ASSOC);
    }

    public function options(): array
    {
        $sql = "SELECT id AS value, mark AS  text FROM spots ORDER BY mark;";
        $Sth = $this->pdo->prepare($sql);
        $Sth->execute();
        return $Sth->fetchAll(\PDO::FETCH_ASSOC);
    }

}