<?php

namespace Controllers;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use \Models\Bookings;

final class ListBookings
{
    private $Bookings;

    public function __construct(Bookings $Bookings)
    {
        $this->Bookings = $Bookings;
    }

    public function __invoke(ServerRequestInterface $request, ResponseInterface $response): ResponseInterface
    {
        $reqData = $request->getParsedBody();
        $resData = [];
        $collection = $this->Bookings->fetch();
        if ( count($collection) ) {
            $resData['collection'] = $collection;
        } else {
            $resData['msg'] = 'Brak rezerwacji';
        }
        $response->getBody()->write( json_encode($resData) );
        return $response->withHeader('Content-Type', 'application/json');
    }
}