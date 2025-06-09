<?php

namespace JoeJuiceBank\Domain\Model;

use JoeJuiceBank\Core\Exceptions\BankException;
use JoeJuiceBank\Core\Logger\LoggerFactory;
use JoeJuiceBank\Domain\Interfaces\IAccount;
use JoeJuiceBank\Domain\Interfaces\IBank;
use JoeJuiceBank\Domain\Services\TransferService;
use JoeJuiceBank\Domain\ValueObject\Address;
use Money\Money;

class Bank implements IBank
{
    /** @var Account[] */
    private array $accounts = [];
    /** @var string */
    private $name;
    /** @var Address */
    private $address;
    /** @var \DateTimeImmutable */
    private $createdAt;

    /** @var TransferService */
    private $transferService;

    /**
     * Bank constructor.
     * @param string $name
     * @param Address $address
     * @param \DateTimeImmutable $createdAt
     * @param TransferService $transferService
     */
    private function __construct(
        string $name,
        Address $address,
        \DateTimeImmutable $createdAt,
        TransferService $transferService
    ) {
        $this->name = $name;
        $this->address = $address;
        $this->createdAt = $createdAt;
        $this->transferService = $transferService;
    }

    public static function create(
        string $name,
        Address $address,
        ?\DateTimeImmutable $createdAt = null
    ): self {
        if (strlen($name) < 2) {
            throw new BankException('Name must be at least 2 characters long');
        }

        return new self(
            $name,
            $address,
            $createdAt ?? new \DateTimeImmutable(),
            $transferService ?? new TransferService(LoggerFactory::create())
        );
    }

    public function addAccount(Account $account): void
    {
        $this->accounts[] = $account;
    }

    /**
     * @return Account[]
     */
    public function getAccounts(): array
    {
        return $this->accounts;
    }

    public function getPostalAddressForPrintLabels(): string
    {
        return $this->name . "\n" . $this->address;
    }

    public function transfer(IAccount $fromAccount, IAccount $toAccount, Money $amount): void
    {
        if (!in_array($fromAccount, $this->accounts, true) || !in_array($toAccount, $this->accounts, true)) {
            throw new \DomainException('Both accounts must belong to this bank.');
        }

        $this->transferService->transfer($fromAccount, $toAccount, $amount);
    }
}