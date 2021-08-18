<?php

namespace Jungi\Common;

/**
 * Returns true:
 *   if $a implements Equatable and is equal with $b
 *   if $a and $b are equal, and of the same type "==="
 * otherwise false
 */
function equals($a, $b): bool
{
    if ($a instanceof Equatable) {
        try {
            return $a->equals($b);
        } catch (\TypeError $e) {
            return false;
        }
    }

    return $a === $b;
}

/**
 * Returns true if a value is present in an iterable,
 * otherwise false.
 */
function in_iterable($value, iterable $iterable): bool
{
    foreach ($iterable as $iteratedValue) {
        if (equals($value, $iteratedValue)) {
            return true;
        }
    }

    return false;
}
