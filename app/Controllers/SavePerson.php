<?php

namespace Controllers;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use \Models\Person;

final class SavePerson
{
    private $Person;

    public function __construct(Person $Person)
    {
        $this->Person = $Person;
    }

    public function __invoke(ServerRequestInterface $request, ResponseInterface $response): ResponseInterface
    {
        $reqData = $request->getParsedBody();
        $resData = [];
        if ( $this->Person->save($reqData) ) {
            $resData['msg'] = 'Dane zostały zapisane';
            $resData['model'] = $this->Person->get();
        } else {
            $resData['msg'] = 'Wystąpił błąd';
            $resData['model'] =json_decode($reqData['person']);
        }
        $response->getBody()->write( json_encode($resData) );
        return $response->withHeader('Content-Type', 'application/json');
    }
}