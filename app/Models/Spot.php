<?php

namespace Models;

use PDO;

class Spot
{
    public $id;
    public $mark;
    public $descr;
    public $tools = [];

    protected $pdo;

    public function __construct(PDO $db)
    {
        $this->pdo = $db;
    }

    private function form2model(array $form)
    {
        $this->id = $form['id'];
        $this->mark = $form['mark'];
        $this->descr = $form['descr'];
    }

    private function model2form():array
    {
        $form = [];
        $form['id'] = $this->id;
        $form['mark'] = $this->mark;
        $form['descr'] = $this->descr;
        $form['tools'] = $this->tools;
        return $form;
    }

    public function get(int $id=null): array
    {
        if ( $id ) {
            $sql = 'SELECT * FROM spots WHERE id = ?';
            $Sth = $this->pdo->prepare($sql);
            $Sth->setFetchMode(\PDO::FETCH_INTO, $this);
            $Sth->execute([$id]);
            $Sth->fetch();

            $sql = 'SELECT id, model, mark FROM spot_tools_vw WHERE spot_id= :spot_id;';
            $Sth = $this->pdo->prepare($sql);
            $Sth->execute([$this->id]);
            $this->tools = $Sth->fetchAll(\PDO::FETCH_ASSOC);
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
        $sql = 'INSERT INTO spots (mark, descr) VALUES (:mark,:descr) RETURNING id;';
        $Sth = $this->pdo->prepare($sql);
        $Sth->execute($record);
        $this->id = $Sth->fetchColumn();
        return $result = $this->id ? true : false ;
    }

    private function update(array $record): bool
    {
        $sql = 'UPDATE spots SET mark = :mark, descr = :descr WHERE id=:id;';
        $Sth = $this->pdo->prepare($sql);
        $Sth->execute($record);
        return $result = $Sth->rowCount() ? true : false ;
    }

    public function save(array $form): bool
    {
        $this->form2model($form);
        $record = $this->prepare();
        unset($record['tools']);
        if ( is_null($this->id) ) {
            unset($record['id']);
            return $this->insert($record);
        } else {
            return $this->update($record);
        }
    }

    public function delete(int $id): bool
    {
        $sql = 'SELECT COUNT(1) FROM bookings WHERE spot_id=:id;';
        $Sth = $this->pdo->prepare($sql);
        $Sth->execute([$id]);
        if ( $Sth->fetchColumn() ) {
            return false;
        }
        $sql = 'DELETE FROM spots WHERE id=:id;';
        $Sth = $this->pdo->prepare($sql);
        $Sth->execute([$id]);
        return $result = $Sth->rowCount() ? true : false ;
    }

    public function linkTool(int $spot_id, int $tool_id): bool
    {
        $data = [
            'spot_id' => $spot_id,
            'tool_id' => $tool_id,
        ];
        $sql = 'INSERT INTO spot_tools (spot_id, tool_id) VALUES (:spot_id, :tool_id) RETURNING id;';
        $Sth = $this->pdo->prepare($sql);
        $Sth->execute($data);
        $this->id = $Sth->fetchColumn();
        return $result = $this->id ? true : false ;
    }

    public function detachTool(int $id): bool
    {
        $sql = 'DELETE FROM spot_tools WHERE id=:id;';
        $Sth = $this->pdo->prepare($sql);
        $Sth->execute([$id]);
        return $result = $Sth->rowCount() ? true : false ;
    }

}