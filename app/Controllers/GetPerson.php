<?php

namespace Controllers;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use \Models\Person;

final class GetPerson
{
    private $Person;

    public function __construct(Person $Person)
    {
        $this->Person = $Person;
    }

    public function __invoke(
        ServerRequestInterface $request,
        ResponseInterface $response,
        array $args = []
    ): ResponseInterface
    {
        $id = (int) $args['id'];
        $record = $this->Person->get($id);
        if ( is_null($record['id']) ) {
            $resData['msg'] = 'Brak danych';
        } else {
            $resData['model'] = $record;
        }
        $response->getBody()->write( json_encode($resData) );
        return $response->withHeader('Content-Type', 'application/json');
    }
}