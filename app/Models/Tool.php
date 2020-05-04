<?php

namespace Models;

use PDO;

class Tool
{
    public $id;
    public $kind;
    public $model;
    public $mark;
    public $year;
    public $value;
    public $descr;

    protected $pdo;

    public function __construct(PDO $db)
    {
        $this->pdo = $db;
    }

    private function form2model(array $form)
    {
        $this->id = $form['id'];
        $this->kind = $form['kind'];
        $this->model = $form['model'];
        $this->mark = $form['mark'];
        $this->year = $form['year'];
        $this->value = $form['value'];
        $this->descr = $form['descr'];
    }

    private function model2form():array
    {
        $form = [];
        $form['id'] = $this->id;
        $form['kind'] = $this->kind;
        $form['model'] = $this->model;
        $form['mark'] = $this->mark;
        $form['year'] = $this->year;
        $form['value'] = $this->value;
        $form['descr'] = $this->descr;
        return $form;
    }

    public function get(int $id=null): array
    {
        if ( $id ) {
            $sql = 'SELECT * FROM tools WHERE id = ?';
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
        $sql = "INSERT INTO tools (kind, model, mark, year, value, descr) VALUES (:kind, :model, :mark, :year, :value, :descr) RETURNING id;";
        $Sth = $this->pdo->prepare($sql);
        $Sth->execute($record);
        $this->id = $Sth->fetchColumn();
        return $result = $this->id ? true : false ;
    }

    private function update(array $record): bool
    {
        $sql = 'UPDATE tools SET kind = :kind, model = :model, mark = :mark, year = :year, value = :value, descr = :descr WHERE id=:id;';
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
        $sql = 'DELETE FROM tools WHERE id=:id;';
        $Sth = $this->pdo->prepare($sql);
        $Sth->execute([$id]);
        return $result = $Sth->rowCount() ? true : false ;
    }

}