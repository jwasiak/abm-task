<?php

use Psr\Container\ContainerInterface;
use Selective\Config\Configuration;
use Slim\App;
use Slim\Factory\AppFactory;
use Slim\Middleware\ErrorMiddleware;

return [
    Configuration::class => function () {
        return new Configuration(require __DIR__ . '/settings.php');
    },

    App::class => function (ContainerInterface $container) {
        AppFactory::setContainer($container);
        $app = AppFactory::create();
        return $app;
    },

    ErrorMiddleware::class => function (ContainerInterface $container) {
        $app = $container->get(App::class);
        $settings = $container->get(Configuration::class)->getArray('error_handler_middleware');

        return new ErrorMiddleware(
            $app->getCallableResolver(),
            $app->getResponseFactory(),
            (bool)$settings['display_error_details'],
            (bool)$settings['log_errors'],
            (bool)$settings['log_error_details']
        );
    },

    PDO::class => function (ContainerInterface $container) {
        $db = $container->get(Configuration::class)->getArray('db');
        $dsn = 'pgsql:host=' . $db['host'] . ';port=' . $db['port'] . ';dbname=' . $db['dbname'];
        return new PDO($dsn, $db['user'], $db['password'], $db['flags']);
    }

];