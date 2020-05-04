<?php

namespace Controllers;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use \Models\Tools;

final class ListTools
{
    private $Tools;

    public function __construct(Tools $Tools)
    {
        $this->Tools = $Tools;
    }

    public function __invoke(ServerRequestInterface $request, ResponseInterface $response): ResponseInterface
    {
        $reqData = $request->getParsedBody();
        $resData = [];
        $collection = $this->Tools->fetch();
        if ( count($collection) ) {
            $resData['collection'] = $collection;
        } else {
            $resData['msg'] = 'Brak wyposażenia';
        }
        $response->getBody()->write( json_encode($resData) );
        return $response->withHeader('Content-Type', 'application/json');
    }
}