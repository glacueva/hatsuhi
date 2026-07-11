<?php

namespace App\Enums;

enum Month: string
{
    case JANUARY = '1';
    case FEBRUARY = '2';
    case MARCH = '3';
    case APRIL = '4';
    case MAY = '5';
    case JUNE = '6';
    case JULY = '7';
    case AUGUST = '8';
    case SEPTEMBER = '9';
    case OCTOBER = '10';
    case NOVEMBER = '11';
    case DECEMBER = '12';

    public function label(): string
    {
        return self::options()[$this->value] ?? throw new \UnexpectedValueException("Unknown month: {$this->value}");
    }

    public static function options(): array
    {
        return __('app.months');
    }

    public static function shortOptions(bool $onlyValues = false): array
    {
        $set = [];
        foreach (self::options() as $month_number => $month_name) {
            $set[$month_number] = substr($month_name, 0, min(3, strlen($month_name)));
        }

        return $onlyValues ? array_values($set) : $set;
    }
}
