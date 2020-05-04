<?php

use Slim\App;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

return function (App $app)
{
     $app->get('/', \Controllers\MainPage::class);

     $app->get('/bookings', \Controllers\ListBookings::class);
     $app->get('/booking/{id}', \Controllers\GetBooking::class);
     $app->post('/booking', \Controllers\SaveBooking::class);
     $app->delete('/booking/{id}', \Controllers\DeleteBooking::class);

     $app->post('/person', \Controllers\SavePerson::class);
     $app->get('/persons', \Controllers\ListPersons::class);
     $app->get('/person/{id}', \Controllers\GetPerson::class);
     $app->delete('/person/{id}', \Controllers\DeletePerson::class);
     $app->get('/persons-options', \Controllers\PersonsOptions::class);

     $app->post('/spot', \Controllers\SaveSpot::class);
     $app->get('/spots', \Controllers\ListSpots::class);
     $app->get('/spot/{id}', \Controllers\GetSpot::class);
     $app->delete('/spot/{id}', \Controllers\DeleteSpot::class);
     $app->put('/spot/{spot}/tool/{tool}', \Controllers\LinkTool::class);
     $app->delete('/detach-tool/{id}', \Controllers\DetachTool::class);
     $app->get('/spots-options', \Controllers\SpotsOptions::class);
     $app->get('/spot-tools/{id}', \Controllers\SpotTools::class);


     $app->post('/tool', \Controllers\SaveTool::class);
     $app->get('/tools', \Controllers\ListTools::class);
     $app->get('/available-tools', \Controllers\AvailableTools::class);
     $app->get('/tool/{id}', \Controllers\GetTool::class);
     $app->delete('/tool/{id}', \Controllers\DeleteTool::class);
};
