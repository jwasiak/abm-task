<?php

namespace Controllers;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use \Models\Spot;

final class LinkTool
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
        $spotId = (int) $args['spot'];
        $toolId = (int) $args['tool'];
        if ( $this->Spot->linkTool($spotId, $toolId) ) {
            $resData['msg'] = 'Przypisano wyposażenie do miejsca';
        } else {
            $resData['msg'] = 'Nie można przypisać wyposażenia';
        }
        $response->getBody()->write( json_encode($resData) );
        return $response->withHeader('Content-Type', 'application/json');
    }
}