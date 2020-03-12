<?php

declare(strict_types=1);

namespace Blank\Infrastructure\HTTP;

use Exception;
use Psr\Http\Message\ResponseFactoryInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Log\LoggerInterface;
use Slim\Exception\HttpBadRequestException;
use Slim\Exception\HttpException;
use Slim\Exception\HttpForbiddenException;
use Slim\Exception\HttpMethodNotAllowedException;
use Slim\Exception\HttpNotFoundException;
use Slim\Exception\HttpNotImplementedException;
use Slim\Exception\HttpSpecializedException;
use Slim\Exception\HttpUnauthorizedException;
use Slim\Handlers\ErrorHandler;
use Slim\Interfaces\CallableResolverInterface;
use Throwable;

class HttpErrorHandler extends ErrorHandler
{
    public const BAD_REQUEST             = 'BAD_REQUEST';
    public const INSUFFICIENT_PRIVILEGES = 'INSUFFICIENT_PRIVILEGES';
    public const NOT_ALLOWED             = 'NOT_ALLOWED';
    public const NOT_IMPLEMENTED         = 'NOT_IMPLEMENTED';
    public const RESOURCE_NOT_FOUND      = 'RESOURCE_NOT_FOUND';
    public const SERVER_ERROR            = 'SERVER_ERROR';
    public const UNAUTHENTICATED         = 'UNAUTHENTICATED';

    private LoggerInterface $logger;

    public function __construct(
        CallableResolverInterface $callableResolver,
        ResponseFactoryInterface $responseFactory,
        LoggerInterface $logger
    ) {
        parent::__construct($callableResolver, $responseFactory);
        $this->logger = $logger;
    }

    protected function writeToErrorLog(): void
    {
        if ($this->exception instanceof HttpSpecializedException) {
            return;
        }
        parent::writeToErrorLog();
    }


    protected function respond(): ResponseInterface
    {
        $exception = $this->exception;
        $statusCode = 500;
        $type = self::SERVER_ERROR;
        $description = 'An internal error has occurred while processing your request.';

        if ($exception instanceof HttpException) {
            $statusCode = $exception->getCode();
            $description = $exception->getMessage();

            if ($exception instanceof HttpNotFoundException) {
                $type = self::RESOURCE_NOT_FOUND;
            } elseif ($exception instanceof HttpMethodNotAllowedException) {
                $type = self::NOT_ALLOWED;
            } elseif ($exception instanceof HttpUnauthorizedException) {
                $type = self::UNAUTHENTICATED;
            } elseif ($exception instanceof HttpForbiddenException) {
                $type = self::UNAUTHENTICATED;
            } elseif ($exception instanceof HttpBadRequestException) {
                $type = self::BAD_REQUEST;
            } elseif ($exception instanceof HttpNotImplementedException) {
                $type = self::NOT_IMPLEMENTED;
            }
        }

        if (
            !($exception instanceof HttpException)
            && ($exception instanceof Exception || $exception instanceof Throwable)
            && $this->displayErrorDetails
        ) {
            $description = $exception->getMessage();
        }

        $error = [
            'error' => [
                'type' => $type,
                'description' => $description,
            ],
        ];

        $payload = json_encode($error, JSON_PRETTY_PRINT);

        $response = $this->responseFactory->createResponse($statusCode);
        $response->getBody()->write($payload);

        return $response;
    }

    protected function logError(string $error): void
    {
        //parent::logError($error);
        $this->logger->error($error);
    }
}
