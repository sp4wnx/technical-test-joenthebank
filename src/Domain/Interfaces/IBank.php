<?php

namespace JoeJuiceBank\Domain\Interfaces;

use JoeJuiceBank\Domain\Model\Account;

interface IBank
{
    /**
     * Get the postal address for print labels.
     *
     * @return string
     */
    public function getPostalAddressForPrintLabels(): string;

    /**
     * Add an account to the bank.
     *
     * @param Account $account
     * @return void
     */
    public function addAccount(Account $account): void;

    /**
     * Get all accounts in the bank.
     *
     * @return Account[]
     */
    public function getAccounts(): array;
}