<?php

namespace Controllers;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use \Models\Booking;

final class GetBooking
{
    private $Booking;

    public function __construct(Booking $Booking)
    {
        $this->Booking = $Booking;
    }

    public function __invoke(
        ServerRequestInterface $request,
        ResponseInterface $response,
        array $args = []
    ): ResponseInterface
    {
        $id = (int) $args['id'];
        $record = $this->Booking->get($id);
        if ( is_null($record['id']) ) {
            $resData['msg'] = 'Brak danych';
        } else {
            $resData['model'] = $record;
        }
        $response->getBody()->write( json_encode($resData) );
        return $response->withHeader('Content-Type', 'application/json');
    }
}