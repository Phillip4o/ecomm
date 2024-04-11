<?php

namespace App\Enum;

enum PaymentMethod: string
{
    case Cash = 'CASH';
    case Bank = 'BANK';
    case Card = 'CARD';
}
