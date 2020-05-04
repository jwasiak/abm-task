<?php

namespace Controllers;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use \Models\Spot;

final class DeleteSpot
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
        if ( $this->Spot->delete($id) ) {
            $resData['msg'] = 'Rekord usunięto';
        } else {
            $resData['msg'] = 'Nie można usunąć rekordu';
        }
        $response->getBody()->write( json_encode($resData) );
        return $response->withHeader('Content-Type', 'application/json');
    }
}