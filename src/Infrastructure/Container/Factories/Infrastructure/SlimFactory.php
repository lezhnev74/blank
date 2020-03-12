<?php

declare(strict_types=1);

namespace Blank\Infrastructure\Container\Factories\Infrastructure;

use Blank\Infrastructure\HTTP\HttpErrorHandler;
use Blank\Infrastructure\HTTP\ShutdownHandler;
use Psr\Container\ContainerInterface;
use Psr\Log\LoggerInterface;
use Slim\App;
use Slim\Factory\AppFactory;
use Slim\Factory\ServerRequestCreatorFactory;
use Slim\Handlers\Strategies\RequestResponseArgs;

class SlimFactory
{
    public function __invoke(ContainerInterface $container): App
    {
        AppFactory::setContainer($container);
        $app = AppFactory::create();
        $errorHandler = $this->configureErrors($app);
        $this->configureMiddleware($app, $errorHandler);
        $this->configureRoutes($app);
        return $app;
    }

    private function configureErrors(App $app): HttpErrorHandler
    {
        $callableResolver = $app->getCallableResolver();
        $responseFactory = $app->getResponseFactory();
        $logger = $app->getContainer()->get(LoggerInterface::class);

        $serverRequestCreator = ServerRequestCreatorFactory::create();
        $request = $serverRequestCreator->createServerRequestFromGlobals();

        $displayErrorDetails = false;
        $errorHandler = new HttpErrorHandler($callableResolver, $responseFactory, $logger);
        $shutdownHandler = new ShutdownHandler($request, $errorHandler, $displayErrorDetails);
        register_shutdown_function($shutdownHandler);

        return $errorHandler;
    }

    private function configureRoutes(App $app): void
    {
        $routeCollector = $app->getRouteCollector();
        $routeCollector->setDefaultInvocationStrategy(new RequestResponseArgs());

        require_once base_path(implode(DIRECTORY_SEPARATOR, ['src', 'Infrastructure', 'HTTP', 'routes.php']));
    }

    private function configureMiddleware(App $app, HttpErrorHandler $errorHandler): void
    {
        $errorMiddleware = $app->addErrorMiddleware(false, true, config('env') === 'local');
        $errorMiddleware->setDefaultErrorHandler($errorHandler);
    }
}
