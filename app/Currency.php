<?php

namespace App;

enum Currency: string
{
    case USD = 'USD';
    case EUR = 'EUR';
    case GBP = 'GBP';
    case SGD = 'SGD';
    case JPY = 'JPY';
    case AUD = 'AUD';
    case CAD = 'CAD';
    case MYR = 'MYR';
    case INR = 'INR';
    case CNY = 'CNY';
    case BDT = 'BDT';

    public function symbol(): string
    {
        return match($this) {
            self::USD => '$',
            self::EUR => '€',
            self::GBP => '£',
            self::SGD => 'S$',
            self::JPY => '¥',
            self::AUD => 'A$',
            self::CAD => 'C$',
            self::MYR => 'RM',
            self::INR => '₹',
            self::CNY => '¥',
            self::BDT => '৳',
        };
    }

    public function label(): string
    {
        return match($this) {
            self::USD => 'US Dollar',
            self::EUR => 'Euro',
            self::GBP => 'British Pound',
            self::SGD => 'Singapore Dollar',
            self::JPY => 'Japanese Yen',
            self::AUD => 'Australian Dollar',
            self::CAD => 'Canadian Dollar',
            self::MYR => 'Malaysian Ringgit',
            self::INR => 'Indian Rupee',
            self::CNY => 'Chinese Yuan',
            self::BDT => 'Bangladeshi Taka',
        };
    }

    // List all currencies for a frontend select dropdown
    public static function options(): array
    {
        return array_map(fn($c) => [
            'code'   => $c->value,
            'symbol' => $c->symbol(),
            'label'  => $c->label(),
        ], self::cases());
    }
}
