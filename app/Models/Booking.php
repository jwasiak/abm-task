<?php

namespace Models;

use PDO;

class Booking
{
    public $id;
    public $spot_id;
    public $person_id;
    public $start;
    public $stop;


    protected $pdo;

    public function __construct(PDO $db)
    {
        $this->pdo = $db;
    }

    private function form2model(array $form)
    {
        $this->id = $form['id'];
        $this->spot_id = $form['spot_id'];
        $this->person_id = $form['person_id'];
        $this->start = $form['start'] . ' ' . $form['from'];
        $this->stop = $form['stop'] . ' ' . $form['to'];
    }

    private function model2form():array
    {
        $form = [];
        $form['id'] = $this->id;
        $form['spot_id'] = $this->spot_id;
        $form['person_id'] = $this->person_id;
        $ts = explode(' ', $this->start);
        $form['start'] = $ts[0];
        $form['from'] = substr($ts[1],0,-3);
        $ts = explode(' ', $this->stop);
        $form['stop'] = $ts[0];
        $form['to'] = substr($ts[1],0,-3);
        return $form;
    }

    public function get(int $id=null): array
    {
        if ( $id ) {
            $sql = 'SELECT * FROM bookings WHERE id = ?';
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
        $sql = 'INSERT INTO bookings (spot_id, person_id, start, stop) VALUES (:spot_id, :person_id, :start, :stop) RETURNING id;';
        $Sth = $this->pdo->prepare($sql);
        $Sth->execute($record);
        $this->id = $Sth->fetchColumn();
        return $result = $this->id ? true : false ;
    }

    private function update(array $record): bool
    {
        $sql = 'UPDATE bookings SET spot_id = :spot_id, person_id = :person_id, start = :start, stop = :stop WHERE id=:id;';
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
            if ( $this->freeSpotInsert() ) {
                return $this->insert($record);
            } else {
                return false;
            }
        } else {
            if ( $this->freeSpotUpdate() ) {
                return $this->update($record);
            } else {
                return false;
            }
        }
    }

    public function delete(int $id): bool
    {
        $sql = 'DELETE FROM bookings WHERE id=:id;';
        $Sth = $this->pdo->prepare($sql);
        $Sth->execute([$id]);
        return $result = $Sth->rowCount() ? true : false ;
    }

    private function freeSpotInsert() : bool
    {
        $data = [
            'spot_id' => $this->spot_id,
            'start' => $this->start,
            'stop' => $this->stop,
        ];
        $sql = 'SELECT COUNT(1) FROM bookings WHERE spot_id = :spot_id
            AND (
            ( start < :start AND stop > :stop )
            OR
            ( start BETWEEN :start AND  :stop )
            OR
            ( stop BETWEEN :start AND  :stop )
            )
            AND
            ( start <> :stop AND stop <> :start );';
        $Sth = $this->pdo->prepare($sql);
        $Sth->execute($data);
        return $result = ( $Sth->fetchColumn() ) === 0 ? true : false ;

    }

     private function freeSpotUpdate() : bool
    {
        $data = [
            'id' => $this->id,
            'spot_id' => $this->spot_id,
            'start' => $this->start,
            'stop' => $this->stop,
        ];
        $sql = 'SELECT COUNT(1) FROM bookings WHERE id <> :id AND spot_id = :spot_id
            AND (
            ( start < :start AND stop > :stop )
            OR
            ( start BETWEEN :start AND  :stop )
            OR
            ( stop BETWEEN :start AND  :stop )
            )
            AND
            ( start <> :stop AND stop <> :start );';
        $Sth = $this->pdo->prepare($sql);
        $Sth->execute($data);
        return $result = ( $Sth->fetchColumn() ) === 0 ? true : false ;

    }



}