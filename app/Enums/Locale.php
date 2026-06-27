<?php

namespace App\Enums;

enum Locale: string
{
    case EN = 'en'; // english
    case CA = 'ca'; // catalan
    case EL = 'el'; // greek
    case ES = 'es'; // spanish
    case ZH = 'zh_CN'; // chinese

    public function label(): string
    {
        return match ($this) {
            self::EN => 'English',
            self::CA => 'Català',
            self::EL => 'ελληνικά',
            self::ES => 'Español',
            self::ZH => '中文',
            default => throw new \UnexpectedValueException("Unknown locale: {$this->value}"),
        };
    }

    public static function options(): array
    {
        $options = [];

        foreach (self::cases() as $locale) {
            $options[$locale->value] = $locale->label();
        }

        return $options;
    }
}
