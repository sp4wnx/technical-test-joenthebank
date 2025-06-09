<?php

namespace JoeJuiceBank\Domain\Model;

use JoeJuiceBank\Domain\Enums\TransactionType;
use JoeJuiceBank\Domain\Interfaces\IAccount;
use JoeJuiceBank\Domain\ValueObject\Transaction;
use Money\Money;
use Ramsey\Uuid\Uuid;
use Ramsey\Uuid\UuidInterface;

/**
 * Class Account
 * @package JoeJuiceBank\Domain\Model
 */
class Account implements IAccount
{
    /** @var UuidInterface */
    private $id;
    /** @var Money */
    private $balance;
    /** @var Transaction[] */
    private $transactions;

    /**
     * Account constructor.
     * @param UuidInterface $id
     * @param Money $balance
     * @param Transaction[] $transactions
     */
    private function __construct(
        UuidInterface $id,
        Money $balance,
        array $transactions = []
    ) {
        $this->id = $id;
        $this->balance = $balance;
        $this->transactions = $transactions;
    }

    /**
     * @param UuidInterface|null $id
     * @param Money|null $balance
     * @param array $transactions
     * @return self
     */
    public static function create(
        ?UuidInterface $id = null,
        ?Money $balance = null,
        array $transactions = []
    ): self {
        $id ??= Uuid::uuid4();
        $balance ??= Money::DKK(100000); // starts with 1000 DKK

        return new self($id, $balance, $transactions);
    }

    /**
     * @param Money $deposit
     */
    private function transferIn(Money $deposit): void
    {
        $this->balance = $this->balance->add($deposit);
    }

    /**
     * @param Money $withdrawal
     */
    private function transferOut(Money $withdrawal): void
    {
        $this->balance = $this->balance->subtract($withdrawal);
    }

    /**
     * Transfer money to another account.
     * This is the only public method that should be used for money transfers.
     *
     * @param Money $amount
     * @param IAccount $toAccount
     * @return void
     */
    public function transfer(Money $amount, IAccount $toAccount): void
    {
        if ($amount->isNegative()) {
            throw new \DomainException('Transfer amount must be positive');
        }

        if ($this->balance < $amount) {
            throw new \DomainException('Insufficient funds');
        }

        $this->transferOut($amount);
        $toAccount->transferIn($amount);
    }

    /**
     * @return Money
     */
    public function getBalance(): Money
    {
        return $this->balance;
    }

    /**
     * @param Transaction $transaction
     */
    public function addTransaction(Transaction $transaction): void
    {
        $this->transactions[] = $transaction;
    }

    /**
     * @return Transaction[]
     */
    public function getTransferOut(): array
    {
        return array_filter($this->transactions, fn (Transaction $transaction) => ($transaction->getType() === TransactionType::TRANSFER_OUT));
    }

    /**
     * @return Transaction[]
     */
    public function getTransferIn(): array
    {
        return array_filter($this->transactions, fn (Transaction $transaction) => ($transaction->getType() === TransactionType::TRANSFER_IN));
    }

    /**
     * @return UuidInterface
     */
    public function getId(): UuidInterface
    {
        return $this->id;
    }
}