<?php

namespace App\Enum;

enum OrderStatus: string
{
    case Paid = 'PAID';
    case Pending = 'PENDING';
    case Refunded = 'REFUNDED';
    case Deleted = 'DELETED';
    case Completed = 'COMPLETED';
}
