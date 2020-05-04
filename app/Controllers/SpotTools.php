<?php

namespace Controllers;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use \Models\Spot;

final class SpotTools
{
    private $Spot;

    public function __construct(Spot $Spot)
    {
        $this->Spot = $Spot;
    }

    public function __invoke(
        ServerRequestInterface $request,
        ResponseInterface $response,
        array $args = []
    ): ResponseInterface
    {
        $id = (int) $args['id'];
        $record = $this->Spot->get($id);
        if ( is_null($record['id']) ) {
            $resData['collection'] = [];
        } else {
            $resData['collection'] = $record['tools'];
        }
        $response->getBody()->write( json_encode($resData) );
        return $response->withHeader('Content-Type', 'application/json');
    }
}