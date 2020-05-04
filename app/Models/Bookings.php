<?php

namespace Models;

use PDO;

class Bookings
{
    protected $pdo;

    public function __construct(PDO $db)
    {
        $this->pdo = $db;
    }


    public function fetch(): array
    {
        $sql = 'SELECT * FROM bookings_vw ORDER BY id DESC;';
        $Sth = $this->pdo->prepare($sql);
        $Sth->execute();
        return $Sth->fetchAll(\PDO::FETCH_ASSOC);
    }

}