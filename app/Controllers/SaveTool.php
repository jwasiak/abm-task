<?php

namespace Controllers;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use \Models\Tool;

final class SaveTool
{
    private $Tool;

    public function __construct(Tool $Tool)
    {
        $this->Tool = $Tool;
    }

    public function __invoke(ServerRequestInterface $request, ResponseInterface $response): ResponseInterface
    {
        $reqData = $request->getParsedBody();
        $resData = [];
        if ( $this->Tool->save($reqData) ) {
            $resData['msg'] = 'Dane zostały zapisane';
            $resData['model'] = $this->Tool->get();
        } else {
            $resData['msg'] = 'Wystąpił błąd';
            $resData['model'] =json_decode($reqData['Tool']);
        }
        $response->getBody()->write( json_encode($resData) );
        return $response->withHeader('Content-Type', 'application/json');
    }
}