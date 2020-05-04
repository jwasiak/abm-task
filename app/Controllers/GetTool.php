<?php

namespace Controllers;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use \Models\Tool;

final class GetTool
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
        $record = $this->Tool->get($id);
        if ( is_null($record['id']) ) {
            $resData['msg'] = 'Brak danych';
        } else {
            $resData['model'] = $record;
        }
        $response->getBody()->write( json_encode($resData) );
        return $response->withHeader('Content-Type', 'application/json');
    }
}