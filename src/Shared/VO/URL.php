<?php

declare(strict_types=1);

namespace Blank\Shared\VO;

use Webmozart\Assert\Assert;

/**
 * Represents URL in the app
 */
final class URL
{
    /** @var string */
    private $url;

    private function __construct(string $url)
    {
        Assert::notFalse(filter_var($url, FILTER_VALIDATE_URL));

        $this->url = $url;
    }

    public static function fromString(string $url): self
    {
        return new self($url);
    }

    public function toString(): string
    {
        return $this->url;
    }

    public function equals($other): bool
    {
        if (!$other instanceof self) {
            return false;
        }

        return $this->url === $other->url;
    }

    public function __toString(): string
    {
        return $this->url;
    }

    public function getDomain(): string
    {
        return parse_url($this->url, PHP_URL_HOST);
    }
}
