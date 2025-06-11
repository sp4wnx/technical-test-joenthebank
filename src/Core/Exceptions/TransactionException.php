<?php

namespace JoeJuiceBank\Core\Exceptions;

class TransactionException extends \RuntimeException
{
    public function __construct(string $message = "", int $code = 0, ?\Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }
}