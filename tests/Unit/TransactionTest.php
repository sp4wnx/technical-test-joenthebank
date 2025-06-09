<?php

use JoeJuiceBank\Core\Exceptions\TransactionException;
use JoeJuiceBank\Domain\Enums\TransactionType;
use JoeJuiceBank\Domain\ValueObject\Transaction;
use Money\Money;
use Ramsey\Uuid\Uuid;
use Ramsey\Uuid\UuidInterface;

describe("Transaction", function () {
    it("can be created with name and address", function () {
        $accountId = Uuid::uuid4();
        $createdAt = new DateTimeImmutable();

        $transaction = Transaction::create(
            amount: Money::DKK(20000),
            type: TransactionType::TRANSFER_IN,
            accountId: $accountId,
            createdAt: $createdAt,
        );

        expect($transaction)->toBeInstanceOf(Transaction::class);
        expect($transaction->getId())->toBeInstanceOf(UuidInterface::class);
        expect($transaction->getAmount())->toEqual(Money::DKK(20000));
        expect($transaction->getAccountId())->toBe($accountId);
        expect($transaction->getCreatedAt())->toEqual($createdAt);
    });

    it("cannot be created with negative amount", function () {
        expect(fn() => (Transaction::create(
            amount: Money::DKK(-20000),
            type: TransactionType::TRANSFER_IN,
            accountId: Uuid::uuid4()
        )))->toThrow(TransactionException::class, 'Amount must be positive');
    });
});