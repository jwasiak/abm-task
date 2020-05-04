<?php

namespace Models;

use PDO;

class Person
{
    public $id;
    public $name;
    public $surname;
    public $phone;
    public $email;
    public $notes;

    protected $pdo;

    public function __construct(PDO $db)
    {
        $this->pdo = $db;
    }

    private function form2model(array $form)
    {
        $this->id = $form['id'];
        $this->name = $form['name'];
        $this->surname = $form['surname'];
        $this->phone = $form['phone'];
        $this->email = $form['email'];
        $this->notes = $form['notes'];
    }

    private function model2form():array
    {
        $form = [];
        $form['id'] = $this->id;
        $form['name'] = $this->name;
        $form['surname'] = $this->surname;
        $form['phone'] = $this->phone;
        $form['email'] = $this->email;
        $form['notes'] = $this->notes;
        return $form;
    }

    public function get(int $id=null): array
    {
        if ( $id ) {
            $sql = 'SELECT * FROM persons WHERE id = ?';
            $Sth = $this->pdo->prepare($sql);
            $Sth->setFetchMode(\PDO::FETCH_INTO, $this);
            $Sth->execute([$id]);
            $Sth->fetch();
        }
        return $this->model2form();
    }

    private function prepare(): array
    {
        $record = [];
        $fields = get_object_vars($this);
        unset($fields['pdo']);
        foreach ($fields as $key => $value) {
            $record[$key] = $this->$key;
        }
        return $record;
    }

    private function insert(array $record): bool
    {
        $sql = "INSERT INTO persons
                (name, surname, phone, email, notes)
                VALUES (:name, :surname, :phone, :email, :notes) RETURNING id;";
        $Sth = $this->pdo->prepare($sql);
        $Sth->execute($record);
        $this->id = $Sth->fetchColumn();
        return $result = $this->id ? true : false ;
    }

    private function update(array $record): bool
    {
        $sql = 'UPDATE persons SET
                    name = :name,
                    surname = :surname,
                    phone = :phone,
                    email = :email,
                    notes = :notes
                WHERE id=:id;';
        $Sth = $this->pdo->prepare($sql);
        $Sth->execute($record);
        return $result = $Sth->rowCount() ? true : false ;
    }

    public function save(array $form): bool
    {
        $this->form2model($form);
        $record = $this->prepare();
        if ( is_null($this->id) ) {
            unset($record['id']);
            return $this->insert($record);
        } else {
            return $this->update($record);
        }
    }

    public function delete(int $id): bool
    {
        $sql = 'SELECT COUNT(1) FROM bookings WHERE person_id=:id;';
        $Sth = $this->pdo->prepare($sql);
        $Sth->execute([$id]);
        if ( $Sth->fetchColumn() ) {
            return false;
        }
        $sql = 'DELETE FROM persons WHERE id=:id;';
        $Sth = $this->pdo->prepare($sql);
        $Sth->execute([$id]);
        return $result = $Sth->rowCount() ? true : false ;
    }

}