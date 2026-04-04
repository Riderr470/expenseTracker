<?php

namespace App;

enum Currency: string
{
    case AUD = 'AUD';
    case BDT = 'BDT';
    case CAD = 'CAD';
    case CNY = 'CNY';
    case EUR = 'EUR';
    case GBP = 'GBP';
    case INR = 'INR';
    case JPY = 'JPY';
    case MYR = 'MYR';
    case SGD = 'SGD';
    case USD = 'USD';

    public function symbol(): string
    {
        return match($this) {
            self::AUD => 'A$',
            self::BDT => '৳',
            self::CAD => 'C$',
            self::CNY => '¥',
            self::EUR => '€',
            self::GBP => '£',
            self::INR => '₹',
            self::JPY => '¥',
            self::MYR => 'RM',
            self::SGD => 'S$',
            self::USD => '$',
        };
    }

    public function label(): string
    {
        return match($this) {
            self::AUD => 'Australian Dollar',
            self::BDT => 'Bangladeshi Taka',
            self::CAD => 'Canadian Dollar',
            self::CNY => 'Chinese Yuan',
            self::EUR => 'Euro',
            self::GBP => 'British Pound',
            self::INR => 'Indian Rupee',
            self::JPY => 'Japanese Yen',
            self::MYR => 'Malaysian Ringgit',
            self::SGD => 'Singapore Dollar',
            self::USD => 'US Dollar',
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
