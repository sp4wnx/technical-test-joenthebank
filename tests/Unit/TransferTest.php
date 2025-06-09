<?php

use JoeJuiceBank\Core\Exceptions\TransferException;
use JoeJuiceBank\Domain\Model\Account;
use JoeJuiceBank\Domain\Model\Bank;
use JoeJuiceBank\Domain\ValueObject\Address;
use Money\Money;

describe("Test TransferService", function () {
    beforeEach(function () {
        $this->address = Address::create("Sankt Anne Plads 13 Street", "Copenhagen", "UC", "1250", "Denmark");
        $this->bankName = 'JOE & THE BANK';
        $this->bank = Bank::create($this->bankName, $this->address);

        $this->accountFrom = Account::create();
        $this->accountTo = Account::create();
    });

    test("bank and accounts", function () {
        $this->bank->AddAccount($this->accountFrom);
        $this->bank->addAccount($this->accountTo);

        /* Test 3: Transfer 100 DKK from the first account to the second */
        $this->bank->transfer($this->accountFrom, $this->accountTo, Money::DKK(100));

        expect(count($this->accountFrom->getTransferOut()))->toBe(1);
        expect(count($this->accountTo->getTransferIn()))->toBe(1);
    });

    test("transfer to same account should fail", function () {
        $this->bank->addAccount($this->accountFrom);

        expect(fn() => ($this->bank->transfer($this->accountFrom, $this->accountFrom, Money::DKK(100)))->toThrow(TransferException::class, 'Cannot transfer to same account'));
    });

    test("transfer with negative amount should fail", function () {
        $this->bank->addAccount($this->accountFrom);
        $this->bank->addAccount($this->accountTo);

        expect(fn() => ($this->bank->transfer($this->accountFrom, $this->accountTo, Money::DKK(-100))))->toThrow(TransferException::class, 'Transfer amount must be positive');
    });

    test("transfer with insufficient funds should fail", function () {
        $this->bank->addAccount($this->accountFrom);
        $this->bank->addAccount($this->accountTo);

        expect(fn() => ($this->bank->transfer($this->accountFrom, $this->accountTo, Money::DKK(10000000000))))->toThrow(TransferException::class, 'Insufficient funds');
    });
});
