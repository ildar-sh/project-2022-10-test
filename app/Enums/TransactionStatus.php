<?php

namespace App\Enums;

enum TransactionStatus : string
{
    case New = 'new';
    case Successful = 'successful';
    case Failed = 'failed';
}
