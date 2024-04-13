<?php

namespace App\Enum;

enum OrderStatus: string
{
    case Paid = 'PAID';
    case Cart = 'CART';
    case Refunded = 'REFUNDED';
    case Deleted = 'DELETED';
    case Completed = 'COMPLETED';
}
