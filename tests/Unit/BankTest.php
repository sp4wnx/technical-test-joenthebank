<?php

use JoeJuiceBank\Core\Exceptions\BankException;
use JoeJuiceBank\Core\Exceptions\TransferException;
use JoeJuiceBank\Domain\Model\Bank;
use JoeJuiceBank\Domain\Model\Account;
use JoeJuiceBank\Domain\ValueObject\Address;
use Money\Money;
use Ramsey\Uuid\Uuid;

describe('Bank', function () {
    beforeEach(function () {
        $this->address = Address::create("Street 1", "City", "UT", "12345", "Country");
        $this->bankName = 'JOE & THE BANK';
        $this->bank = Bank::create($this->bankName, $this->address);
    });

    /* Test 1: Initialize a bank test generation of the postal address method */
    it('can be constructed with name and address', function () {
        expect($this->bank)->toBeInstanceOf(Bank::class);
    });

    it('returns an empty accounts list initially', function () {
        expect($this->bank->getAccounts())->toBeArray();
        expect($this->bank->getAccounts())->toBeEmpty();
    });

    /* Test 2: Create two bank accounts and add them to a bank */
    it('can add accounts and retrieve them', function () {
        $account1 = Account::create(Uuid::uuid4());
        $account2 = Account::create(Uuid::uuid4());

        $this->bank->addAccount($account1);
        $this->bank->addAccount($account2);

        $accounts = $this->bank->getAccounts();

        expect($accounts)->toHaveCount(2);
        expect($accounts)->toBeArray();
        expect($accounts)->toContain($account1);
        expect($accounts)->toContain($account2);
    });

    it('formats postal address for print labels correctly', function () {
        expect($this->bank->getPostalAddressForPrintLabels())->toBe($this->bankName . "\n" . $this->address);
        expect($this->bank->getPostalAddressForPrintLabels())->toBe($this->bankName . "\nStreet 1\nCity\nUT\n12345\nCountry");
    });

    it('can transfer money between accounts', function () {
        $from = Account::create();
        $to = Account::create();

        $this->bank->addAccount($from);
        $this->bank->addAccount($to);

        expect($from->getBalance())->toEqual(Money::DKK(100000));
        expect($to->getBalance())->toEqual(Money::DKK(100000));
        $this->bank->transfer($from, $to, Money::DKK(10000));

        expect($from->getBalance())->toEqual(Money::DKK(90000));
        expect($to->getBalance())->toEqual(Money::DKK(110000));

        expect(count($from->getTransferOut()))->toBe(1);
        expect(count($to->getTransferIn()))->toBe(1);
    });

    it('should not allow transfer between same accounts', function () {
        $account = Account::create();

        $this->bank->addAccount($account);

        expect(fn() => $this->bank->transfer($account, $account, Money::DKK(10000)))->toThrow(TransferException::class, 'Cannot transfer to same account');
    });

    it('should not allow create bank with invalid name', function () {
        expect(fn() => (Bank::create("X", $this->address)))->toThrow(BankException::class, 'Name must be at least 2 characters long');
    });
});
