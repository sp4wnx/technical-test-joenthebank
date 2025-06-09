<?php

namespace JoeJuiceBank\Domain\Interfaces;

use JoeJuiceBank\Domain\ValueObject\Transaction;
use Money\Money;
use Ramsey\Uuid\UuidInterface;

interface IAccount {
    /**
     * Create a new account instance.
     *
     * @param UuidInterface|null $id
     * @param Money|null $balance
     * @param array $transactions
     * @return self
     */
    public static function create(
        ?UuidInterface $id = null,
        ?Money $balance = null,
        array $transactions = []
    ): self;

    /**
     * Transfer money to another account.
     * This is the only public method that should be used for money transfers.
     *
     * @param Money $amount
     * @param IAccount $toAccount
     * @return void
     */
    public function transfer(Money $amount, IAccount $toAccount): void;

    /**
     * Get the current balance of the account.
     *
     * @return Money
     */
    public function getBalance(): Money;

    /**
     * Add a transaction to the account.
     *
     * @param Transaction $transaction
     * @return void
     */
    public function addTransaction(Transaction $transaction): void;
}