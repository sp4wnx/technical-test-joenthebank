<?php

namespace JoeJuiceBank\Domain\ValueObject;

use JoeJuiceBank\Core\Exceptions\TransactionException;
use JoeJuiceBank\Domain\Enums\TransactionType;
use Money\Money;
use Ramsey\Uuid\Uuid;
use Ramsey\Uuid\UuidInterface;

class Transaction
{
    /** @var Money */
    private $amount;
    /** @var TransactionType */
    private $type;
    /** @var UuidInterface */
    private $accountId;
    /** @var \DateTimeImmutable */
    private $createdAt;
    /** @var UuidInterface */
    private $id;

    /**
     * Transaction constructor.
     * @param Money $amount
     * @param TransactionType $type
     * @param UuidInterface $accountId
     * @param \DateTimeImmutable $createdAt
     * @param UuidInterface $id
     */
    private function __construct(
        Money $amount,
        TransactionType $type,
        UuidInterface $accountId,
        \DateTimeImmutable $createdAt,
        UuidInterface $id
    ) {
        $this->amount = $amount;
        $this->type = $type;
        $this->accountId = $accountId;
        $this->createdAt = $createdAt;
        $this->id = $id;
    }

    public static function create(
        Money $amount,
        TransactionType $type,
        UuidInterface $accountId,
        ?\DateTimeImmutable $createdAt = null
    ): self {
        if ($amount->isNegative()) {
            throw new TransactionException("Amount must be positive");
        }

        return new self(
            amount: $amount,
            type: $type,
            accountId: $accountId,
            createdAt: $createdAt ?? new \DateTimeImmutable(),
            id: Uuid::uuid4(),
        );
    }

    public function getAmount(): Money
    {
        return $this->amount;
    }

    public function getType(): TransactionType
    {
        return $this->type;
    }

    public function getAccountId(): UuidInterface
    {
        return $this->accountId;
    }

    public function getCreatedAt(): \DateTimeImmutable
    {
        return $this->createdAt;
    }

    public function getId(): UuidInterface
    {
        return $this->id;
    }
}