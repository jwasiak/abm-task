<?php

namespace Controllers;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use \Models\Booking;

final class SaveBooking
{
    private $Booking;

    public function __construct(Booking $Booking)
    {
        $this->Booking = $Booking;
    }

    public function __invoke(ServerRequestInterface $request, ResponseInterface $response): ResponseInterface
    {
        $reqData = $request->getParsedBody();
        $resData = [];
        if ( $this->Booking->save($reqData) ) {
            $resData['msg'] = '';
            $resData['model'] = $this->Booking->get();
        } else {
            $resData['msg'] = 'Wybrane miejsce jest zarezerwowane w tych godzinach';
            $resData['model'] = $reqData;
        }
        $response->getBody()->write( json_encode($resData) );
        return $response->withHeader('Content-Type', 'application/json');
    }
}