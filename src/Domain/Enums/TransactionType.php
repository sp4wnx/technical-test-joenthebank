<?php

namespace JoeJuiceBank\Domain\Enums;

enum TransactionType: string
{
    case TRANSFER_IN = "transfer_in";
    case TRANSFER_OUT = "transfer_out";
}
