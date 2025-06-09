<?php

namespace JoeJuiceBank\Domain\Services;

use JoeJuiceBank\Core\Exceptions\TransferException;
use JoeJuiceBank\Domain\Enums\TransactionType;
use JoeJuiceBank\Domain\Interfaces\IAccount;
use JoeJuiceBank\Domain\ValueObject\Transaction;
use Money\Money;
use Psr\Log\LoggerInterface;

class TransferService
{
    private $logger;

    public function __construct(LoggerInterface $logger)
    {
        $this->logger = $logger;
    }

    /**
     * @throws TransferException
     */
    public function transfer(IAccount $fromAccount, IAccount $toAccount, Money $amount): void
    {
        $this->validateTransfer($fromAccount, $toAccount, $amount);

        try {
            $fromAccount->transfer($amount, $toAccount);

            $this->recordTransfer($fromAccount, $toAccount, $amount);

            $this->logger->info('Transfer successful', [
                'from_account' => $fromAccount->getId()->toString(),
                'to_account' => $toAccount->getId()->toString(),
                'amount' => $amount->getAmount()
            ]);
        } catch (\RuntimeException $e) {
            throw new TransferException('Transfer failed: ' . $e->getMessage(), 0, $e);
        }
    }

    /**
     * @throws TransferException
     */
    private function validateTransfer(IAccount $fromAccount, IAccount $toAccount, Money $amount): void
    {
        if ($fromAccount->getId() === $toAccount->getId()) {
            throw new TransferException('Cannot transfer to same account');
        }

        if ($amount->isNegative()) {
            throw new TransferException('Transfer amount must be positive');
        }

        if ($fromAccount->getBalance() < $amount) {
            throw new TransferException('Insufficient funds');
        }
    }

    private function recordTransfer(IAccount $fromAccount, IAccount $toAccount, Money $amount): void
    {
        $fromAccount->addTransaction(Transaction::create(
            $amount,
            TransactionType::TRANSFER_OUT,
            $toAccount->getId()
        ));

        $toAccount->addTransaction(Transaction::create(
            $amount,
            TransactionType::TRANSFER_IN,
            $fromAccount->getId()
        ));
    }
}