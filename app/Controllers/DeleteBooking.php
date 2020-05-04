<?php

namespace Controllers;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use \Models\Booking;

final class DeleteBooking
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
        if ( $this->Booking->delete($id) ) {
            $resData['msg'] = 'Rezerwację usunięto';
        } else {
            $resData['msg'] = 'Nie można usunąć rezerwacji';
        }
        $response->getBody()->write( json_encode($resData) );
        return $response->withHeader('Content-Type', 'application/json');
    }
}