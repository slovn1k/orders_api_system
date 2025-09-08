<?php

namespace App\Enums;

enum OrderStatus: string
{
    case PENDING = 'pending';
    case DELIVERED = 'delivered';
    case SHIPPED = 'shipped';
    case CANCELED = 'canceled';
}
