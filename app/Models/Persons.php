<?php

namespace Models;

use PDO;

class Persons
{
    protected $pdo;

    public function __construct(PDO $db)
    {
        $this->pdo = $db;
    }


    public function fetch(): array
    {
        $sql = 'SELECT * FROM persons ORDER BY id DESC;';
        $Sth = $this->pdo->prepare($sql);
        $Sth->execute();
        return $Sth->fetchAll(\PDO::FETCH_ASSOC);
    }

    public function options(): array
    {
        $sql = "SELECT id AS value, CONCAT (surname, ' ', name) AS  text FROM persons ORDER BY surname;";
        $Sth = $this->pdo->prepare($sql);
        $Sth->execute();
        return $Sth->fetchAll(\PDO::FETCH_ASSOC);
    }

}