<?php

namespace Controllers;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use \Models\Persons;

final class ListPersons
{
    private $Persons;

    public function __construct(Persons $Persons)
    {
        $this->Persons = $Persons;
    }

    public function __invoke(ServerRequestInterface $request, ResponseInterface $response): ResponseInterface
    {
        $reqData = $request->getParsedBody();
        $resData = [];
        $collection = $this->Persons->fetch();
        if ( count($collection) ) {
            $resData['collection'] = $collection;
        } else {
            $resData['msg'] = 'Brak osób';
        }
        $response->getBody()->write( json_encode($resData) );
        return $response->withHeader('Content-Type', 'application/json');
    }
}