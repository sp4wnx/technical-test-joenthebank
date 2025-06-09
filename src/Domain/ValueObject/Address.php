<?php

namespace JoeJuiceBank\Domain\ValueObject;

use JoeJuiceBank\Core\Exceptions\AddressException;

/**
 * Class Address
 * @package JoeJuiceBank\Domain\ValueObject
 */
class Address
{
    /** @var string */
    private $street;
    /** @var string */
    private $city;
    /** @var string */
    private $state;
    /** @var string */
    private $postalCode;
    /** @var string */
    private $country;

    /**
     * Address constructor.
     * @param string $street
     * @param string $city
     * @param string $state
     * @param string $postalCode
     * @param string $country
     */
    private function __construct(
        string $street,
        string $city,
        string $state,
        string $postalCode,
        string $country
    ) {
        $this->street = $street;
        $this->city = $city;
        $this->state = $state;
        $this->postalCode = $postalCode;
        $this->country = $country;
    }

    /**
     * @param string $street
     * @param string $city
     * @param string $state
     * @param string $postalCode
     * @param string $country
     * @return self
     * @throws AddressException
     */
    public static function create(
        string $street,
        string $city,
        string $state,
        string $postalCode,
        string $country
    ): self {
        if (strlen($street) < 2) {
            throw new AddressException('Street must be at least 2 characters long');
        }

        if (strlen($city) < 2) {
            throw new AddressException('City must be at least 2 characters long');
        }

        if (strlen($state) < 2) {
            throw new AddressException('State must be at least 2 characters long');
        }

        if (strlen($postalCode) < 4) {
            throw new AddressException('Postal code must be at least 4 characters long');
        }

        if (strlen($country) < 2) {
            throw new AddressException('Country must be at least 2 characters long');
        }

        return new self(
            $street,
            $city,
            $state,
            $postalCode,
            $country
        );
    }

    /**
     * @return string
     */
    public function __toString(): string
    {
        return $this->street . "\n" . $this->city . "\n" . $this->state . "\n" . $this->postalCode . "\n" . $this->country;
    }
}