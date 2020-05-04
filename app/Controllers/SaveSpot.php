<?php

namespace Controllers;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use \Models\Spot;

final class SaveSpot
{
    private $Spot;

    public function __construct(Spot $Spot)
    {
        $this->Spot = $Spot;
    }

    public function __invoke(ServerRequestInterface $request, ResponseInterface $response): ResponseInterface
    {
        $reqData = $request->getParsedBody();
        $resData = [];
        if ( $this->Spot->save($reqData) ) {
            $resData['msg'] = 'Dane zostały zapisane';
            $resData['model'] = $this->Spot->get();
        } else {
            $resData['msg'] = 'Wystąpił błąd';
            $resData['model'] =json_decode($reqData['Spot']);
        }
        $response->getBody()->write( json_encode($resData) );
        return $response->withHeader('Content-Type', 'application/json');
    }
}