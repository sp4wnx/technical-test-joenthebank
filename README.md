# Documentation

## Overview

JoeJuiceBank is a simple banking domain model written in PHP, designed for technical assessment and demonstration purposes. It follows modern PHP best practices and is PSR-2 compliant. The project includes:

- Bank and Account domain models
- Value objects for Address and Transaction
- Service for money transfers
- Custom exception handling
- Unit tests using Pest and PHPUnit

## Features

- Create banks and accounts
- Perform money transfers between accounts
- Track account balances and transaction history
- Validate input and business rules
- Exception handling for invalid operations
- Easily extensible and testable architecture

## Project Structure

```
src/
  Core/Exceptions/         # Custom exception classes
  Domain/
    Enums/                # Domain enums (e.g., TransactionType)
    Interfaces/           # Interfaces for domain models
    Model/                # Main domain models (Bank, Account)
    Services/             # Domain services (TransferService)
    ValueObject/          # Value objects (Address, Transaction)
tests/
  Unit/                   # Unit tests for domain logic
  Feature/                # (Optional) Feature tests
```

## Getting Started

### Requirements
- PHP 8.0+
- Composer

### Installation
1. Clone the repository:
   ```zsh
   git clone https://github.com/sp4wnx/technical-test-joenthebank
   cd technical-test-joenthebank
   ```
2. Install dependencies:
   ```zsh
   composer install
   ```

### Running Tests

Run all tests with Pest (recommended):
```zsh
vendor/bin/pest
```
Or with PHPUnit:
```zsh
vendor/bin/phpunit
```

Run test coverage:
```zsh
vendor/bin/pest --coverage
```

## Key Classes & Interfaces

### `Bank`
- Holds accounts and bank details
- Can add accounts and perform transfers

### `Account`
- Represents a bank account with a unique ID and balance
- Tracks transactions (deposits and withdrawals)

### `TransferService`
- Handles money transfers between accounts
- Validates business rules and throws exceptions on errors

### `Address` (Value Object)
- Represents a postal address with validation

### `Transaction` (Value Object)
- Represents a single account transaction (deposit/withdrawal)

### Interfaces
- `IAccount`, `IBank` define contracts for domain models

## Exception Handling
- Custom exceptions for domain errors: `BankException`, `TransferException`, `TransactionException`, `AddressException`

## Testing
- Unit tests are located in `tests/Unit/`
- Uses Pest for expressive, modern PHP testing

## Coding Standards
- PSR-2 compliant (no property promotion, explicit property declarations)
- Docblocks for all public methods and interfaces

## License
This project is for technical assessment and demonstration purposes only.
