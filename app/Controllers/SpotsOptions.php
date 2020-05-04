<?php

namespace Controllers;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use \Models\Spots;

final class SpotsOptions
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
        $collection = $this->Spots->options();
        if ( count($collection) ) {
            $resData['collection'] = $collection;
        } else {
            $resData['collection'] = [];
        }
        $response->getBody()->write( json_encode($resData) );
        return $response->withHeader('Content-Type', 'application/json');
    }
}