<?php

namespace WarehouseApi\Models\Additional;

use Carbon\Carbon;
use WarehouseApi\Models\AbstractModel;

class Address extends AbstractModel implements \JsonSerializable
{
    /** @var string */
    protected $type;
    /** @var string */
    protected $name;
    /** @var string */
    protected $company;
    /** @var string */
    protected $companyNumber;
    /** @var string */
    protected $idTax;
    /** @var string */
    protected $street;
    /** @var string */
    protected $city;
    /** @var string */
    protected $zipCode;
    /** @var string */
    protected $county;
    /** @var string */
    protected $countryCode;

    /**
     * Address constructor.
     * @param int|null $id
     * @param string|null $uid
     * @param string $type
     * @param string $name
     * @param string $company
     * @param string $companyNumber
     * @param string $idTax
     * @param string $street
     * @param string $city
     * @param string $zipCode
     * @param string $county
     * @param string $countryCode
     * @param Carbon|null $created_at
     * @param Carbon|null $updated_at
     * @param Carbon|null $deleted_at
     */
    public function __construct(
        ?int $id,
        ?string $uid,
        string $type,
        string $name,
        string $company,
        string $companyNumber,
        string $idTax,
        string $street,
        string $city,
        string $zipCode,
        string $county,
        string $countryCode,
        ?Carbon $created_at = null,
        ?Carbon $updated_at = null,
        ?Carbon $deleted_at = null
    ) {
        parent::__construct($id, $uid, $created_at, $updated_at, $deleted_at);
        $this->type = $type;
        $this->name = $name;
        $this->company = $company;
        $this->companyNumber = $companyNumber;
        $this->idTax = $idTax;
        $this->street = $street;
        $this->city = $city;
        $this->zipCode = $zipCode;
        $this->county = $county;
        $this->countryCode = $countryCode;
    }

    public static function create($row)
    {
        return new static(
            $row['id'],
            $row['uid'],
            $row['type'],
            $row['name'],
            $row['company'],
            $row['company_number'],
            $row['id_tax'],
            $row['street'],
            $row['city'],
            $row['zip_code'],
            $row['county'],
            $row['country_code'],
            new Carbon($row['created_at']),
            new Carbon($row['updated_at']),
            $row['deleted_at'] === null ? null : new Carbon($row['deleted_at'])
        );
    }

    public function jsonSerialize()
    {
        return [
            'type' => $this->type,
            'name' => $this->name,
            'company' => $this->company,
            'company_number' => $this->companyNumber,
            'id_tax' => $this->idTax,
            'street' => $this->street,
            'city' => $this->city,
            'zip_code' => $this->zipCode,
            'county' => $this->county,
            'country_code' => $this->countryCode,
        ];
    }
}