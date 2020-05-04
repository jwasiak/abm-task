<?php

namespace Controllers;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use \Models\Person;

final class DeletePerson
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
        if ( $this->Person->delete($id) ) {
            $resData['msg'] = 'Rekord usunięto';
        } else {
            $resData['msg'] = 'Nie można usunąć rekordu';
        }
        $response->getBody()->write( json_encode($resData) );
        return $response->withHeader('Content-Type', 'application/json');
    }
}