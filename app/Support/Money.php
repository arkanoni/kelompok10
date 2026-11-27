<?php

namespace App\Support;

use Brick\Math\BigDecimal;

final class Money
{
    public static function multiply(string|int $amount, int $quantity): string
    {
        return (string) BigDecimal::of($amount)->multipliedBy($quantity)->toScale(2);
    }

    /** @param iterable<int|string> $amounts */
    public static function sum(iterable $amounts): string
    {
        $total = BigDecimal::zero();

        foreach ($amounts as $amount) {
            $total = $total->plus($amount);
        }

        return (string) $total->toScale(2);
    }

    public static function format(string|int $amount): string
    {
        [$integer, $fraction] = explode('.', (string) BigDecimal::of($amount)->toScale(2));
        $sign = str_starts_with($integer, '-') ? '-' : '';
        $digits = ltrim($integer, '-');
        $grouped = strrev(implode('.', str_split(strrev($digits), 3)));

        return $sign.$grouped.','.$fraction;
    }
}
