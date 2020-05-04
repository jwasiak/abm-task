<?php

namespace Controllers;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use \Models\Tool;

final class DeleteTool
{
    private $Tool;

    public function __construct(Tool $Tool)
    {
        $this->Tool = $Tool;
    }

    public function __invoke(
        ServerRequestInterface $request,
        ResponseInterface $response,
        array $args = []
    ): ResponseInterface
    {
        $id = (int) $args['id'];
        if ( $this->Tool->delete($id) ) {
            $resData['msg'] = 'Rekord usunięto';
        } else {
            $resData['msg'] = 'Nie można usunąć rekordu';
        }
        $response->getBody()->write( json_encode($resData) );
        return $response->withHeader('Content-Type', 'application/json');
    }
}