<?php

use JoeJuiceBank\Core\Exceptions\AddressException;
use JoeJuiceBank\Domain\ValueObject\Address;

describe('Address', function () {
    it('should create an address with valid values', function () {
        $address = Address::create("Street 1", "City", "UT", "12345", "Country");
        expect($address)->toBeInstanceOf(Address::class);
    });

    it('should throw an exception when street is too short', function () {
        expect(fn() => Address::create("S", "City", "UT", "12345", "Country"))->toThrow(AddressException::class, 'Street must be at least 2 characters long');
    });

    it('should throw an exception when city is too short', function () {
        expect(fn() => Address::create("Street 1", "C", "UT", "12345", "Country"))->toThrow(AddressException::class, 'City must be at least 2 characters long');
    });

    it('should throw an exception when state is too short', function () {
        expect(fn() => Address::create("Street 1", "City", "S", "12345", "Country"))->toThrow(AddressException::class, 'State must be at least 2 characters long');
    });

    it('should throw an exception when postal code is too short', function () {
        expect(fn() => Address::create("Street 1", "City", "UT", "123", "Country"))->toThrow(AddressException::class, 'Postal code must be at least 4 characters long');
    });

    it('should throw an exception when country is too short', function () {
        expect(fn() => Address::create("Street 1", "City", "UT", "12345", "C"))->toThrow(AddressException::class, 'Country must be at least 2 characters long');
    });
});