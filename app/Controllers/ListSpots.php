<?php

namespace Controllers;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use \Models\Spots;

final class ListSpots
{
    private $Spots;

    public function __construct(Spots $Spots)
    {
        $this->Spots = $Spots;
    }

    public function __invoke(ServerRequestInterface $request, ResponseInterface $response): ResponseInterface
    {
        $reqData = $request->getParsedBody();
        $resData = [];
        $collection = $this->Spots->fetch();
        if ( count($collection) ) {
            $resData['collection'] = $collection;
        } else {
            $resData['msg'] = 'Brak miejsc pracy';
        }
        $response->getBody()->write( json_encode($resData) );
        return $response->withHeader('Content-Type', 'application/json');
    }
}