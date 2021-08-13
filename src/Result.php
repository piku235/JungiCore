<?php

namespace Jungi\Core;

/**
 * @template T
 * @template E
 *
 * @author Piotr Kugla <piku235@gmail.com>
 */
abstract class Result
{
    /**
     * @param T $value
     *
     * @see Ok() A shorthand version
     */
    public static function Ok($value = null): self
    {
        return new Ok($value);
    }

    /**
     * @param E $value
     *
     * @see Err() A shorthand version
     */
    public static function Err($value = null): self
    {
        return new Err($value);
    }

    /**
     * @return bool
     */
    abstract public function isOk(): bool;

    /**
     * @return bool
     */
    abstract public function isErr(): bool;

    /**
     * @template U
     *
     * @param callable(T): U $fn
     *
     * @return Result<U, E>
     */
    abstract public function andThen(callable $fn): self;

    /**
     * @template U
     * @template R
     *
     * @param callable(T): Result<U, R> $fn
     *
     * @return Result<U, R>
     */
    abstract public function andThenTo(callable $fn): self;

    /**
     * @return T
     */
    abstract public function unwrap();

    /**
     * @param T $value
     *
     * @return T
     */
    abstract public function unwrapOr($value);

    /**
     * @return E
     */
    abstract public function unwrapErr();

    /**
     * @return Option
     */
    abstract public function asOk(): Option;

    /**
     * @return Option
     */
    abstract public function asErr(): Option;
}

/**
 * @template T
 *
 * @internal
 * @see Result::Ok()
 *
 * @author Piotr Kugla <piku235@gmail.com>
 */
final class Ok extends Result
{
    /** @var T */
    private $value;

    /**
     * @param T $value
     */
    public function __construct($value)
    {
        $this->value = $value;
    }

    public function isOk(): bool
    {
        return true;
    }

    public function isErr(): bool
    {
        return false;
    }

    public function andThen(callable $fn): Result
    {
        return new self($fn($this->value));
    }

    public function andThenTo(callable $fn): Result
    {
        return $fn($this->value);
    }

    /**
     * @return T
     */
    public function unwrap()
    {
        return $this->value;
    }

    /**
     * @param T $value
     *
     * @return T
     */
    public function unwrapOr($value)
    {
        return $this->value;
    }

    public function unwrapErr()
    {
        throw new \LogicException('Called on an "Ok" value.');
    }

    public function asOk(): Option
    {
        return Option::Some($this->value);
    }

    public function asErr(): Option
    {
        return Option::None();
    }
}

/**
 * @template E
 *
 * @internal
 * @see Result::err()
 *
 * @author Piotr Kugla <piku235@gmail.com>
 */
final class Err extends Result
{
    /** @var E */
    private $value;

    /**
     * @param E $value
     */
    public function __construct($value)
    {
        $this->value = $value;
    }

    public function isOk(): bool
    {
        return false;
    }

    public function isErr(): bool
    {
        return true;
    }

    public function andThen(callable $fn): Result
    {
        return $this;
    }

    public function andThenTo(callable $fn): Result
    {
        return $this;
    }

    public function unwrap()
    {
        throw new \LogicException('Called on an "Err" value.');
    }

    public function unwrapOr($value)
    {
        return $value;
    }

    /**
     * @return E
     */
    public function unwrapErr()
    {
        return $this->value;
    }

    public function asOk(): Option
    {
        return Option::None();
    }

    public function asErr(): Option
    {
        return Option::Some($this->value);
    }
}
