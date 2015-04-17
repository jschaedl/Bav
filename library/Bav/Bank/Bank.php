<?php
namespace Bav\Bank;

use Bav\Validator\ValidatorFactory;

class Bank
{
    protected $bankId = '';

    protected $validationType = '';

    protected $agencies;

    private $validator;

    public function __construct($bankId)
    {
        $this->bankId = $bankId;
    }

    public function isValid($account)
    {
        return $this->getValidator()->isValid($account);
    }

    public function getBankId()
    {
        return (string) $this->bankId;
    }

    public function getValidationType()
    {
        return (string) $this->validationType;
    }

    public function setValidationType($validationType)
    {
        return $this->validationType = $validationType;
    }

    public function getAgencies()
    {
        return $this->agencies;
    }

    public function addAgency(Agency $agency)
    {
        $this->agencies[] = $agency;
    }

    public function setAgencies($agencies)
    {
        $this->agencies = $agencies;
    }

    public function getMainAgency()
    {
        foreach ($this->agencies as $agency) {
            if ($agency->isMainAgency())
                return $agency;
        }
    }

    public function getValidator()
    {
        if (is_null($this->validator)) {
            $this->validator = ValidatorFactory::create($this->validationType, $this->getBankId());
        }
        return $this->validator;
    }
}
