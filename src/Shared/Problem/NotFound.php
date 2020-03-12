<?php
declare(strict_types=1);


namespace Blank\Shared\Problem;


use Blank\Shared\VO\StringEntityId;
use Throwable;

final class NotFound extends \RuntimeException
{
    private function __construct($message = "", $code = 0, Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }


    public static function makeFromId(StringEntityId $id): self
    {
        return new static('Resource not found: ' . $id->toString());
    }
}