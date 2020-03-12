<?php

declare(strict_types=1);

namespace Blank\Shared\VO;

use Ramsey\Uuid\Uuid;
use Stringy\Stringy;
use Webmozart\Assert\Assert;

abstract class StringEntityId
{
    /** @var string */
    private $id;

    final public function __construct(string $id)
    {
        $this->validate($id);
        $this->id = $id;
    }

    /**
     * Use plain string
     *
     * @return static
     */
    public static function fromString(string $id)
    {
        return new static((string)Stringy::create($id)->trim());
    }

    /** @return static */
    public static function uuid()
    {
        return self::fromString(Uuid::uuid4()->toString());
    }

    /**
     * Compare two arrays of Ids
     *
     * @var static[] $a
     * @var static[] $b
     */
    public static function equalArrays(array $a, array $b): bool
    {
        Assert::allIsInstanceOf($a, self::class, 'Comparison is only possible on ids');
        Assert::allIsInstanceOf($b, self::class, 'Comparison is only possible on ids');

        if (count($a) !== count($b)) {
            return false;
        }

        sort($a);
        sort($b);

        for ($i = 0, $iMax = count($a); $i < $iMax; $i++) {
            $idA = $a[$i];
            $idB = $b[$i];

            if (!$idA->equals($idB)) {
                return false;
            }
        }

        return true;
    }

    /**
     * Generate array of static instances from simple types (ints(casted to strings) or strings)
     *
     * @return static[]
     */
    public static function fromIds(array $ids): array
    {
        return array_map(function ($id) {
            return static::fromString((string)$id);
        }, $ids);
    }

    /**
     * Convert given Id models to the array of integers
     *
     * @param static[] $ids
     * @return int[]
     */
    public static function toStrings(array $ids): array
    {
        return array_map(function (self $id) {
            return $id->toString();
        }, $ids);
    }

    public function toString(): string
    {
        return $this->id;
    }

    public function equals($other): bool
    {
        if (!$other instanceof self) {
            return false;
        }

        return $this->id === $other->id;
    }

    public function __toString(): string
    {
        return (string)$this->id;
    }

    protected function validate(string $id): void
    {
        // can be extended in sub classes
        Assert::minLength($id, 1);
        Assert::maxLength($id, 200);
    }
}
