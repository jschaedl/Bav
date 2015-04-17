<?php
namespace Bav\Bank;

class Agency
{
    protected $id = 0;

    protected $name = '';

    protected $shortTerm = '';

    protected $city = '';

    protected $postcode = '';

    protected $bic = '';

    protected $pan = '';

    protected $ibanRule = '';

    protected $isMain = false;

    /**
     * Don't create this object directly. Please use Bank->getMainAgency() or 
     * Bank->getAgencies().
     *
     * @param int $id            
     * @param string $name            
     * @param string $shortTerm            
     * @param string $city            
     * @param string $postcode            
     * @param string $bic
     *            might be empty
     * @param string $pan
     *            might be empty
     */
    public function __construct($id, $name, $shortTerm, $city, $postcode, $bic = '', $pan = '', $ibanRule = '', $isMain = false)
    {
        $this->id = (int) $id;
        $this->name = $name;
        $this->shortTerm = $shortTerm;
        $this->city = $city;
        $this->postcode = $postcode;
        $this->bic = $bic;
        $this->pan = $pan;
        $this->ibanRule = $ibanRule;
        $this->isMain = $isMain;
    }

    public function hasBic()
    {
        return !empty($this->bic);
    }

    public function hasIbanRule()
    {
        return !empty($this->ibanRule);
    }

    public function isMainAgency()
    {
        return $this->isMain;
    }

    public function getId()
    {
        return $this->id;
    }

    public function getName()
    {
        return $this->name;
    }

    public function getShortTerm()
    {
        return $this->shortTerm;
    }

    public function getCity()
    {
        return $this->city;
    }

    public function getPostcode()
    {
        return $this->postcode;
    }

    public function getBic()
    {
        if (!$this->hasBIC()) {
            throw new \Bav\Bank\Exception\UndefinedAttributeException('bic');
        }
        return $this->bic;
    }

    public function getPan()
    {
        if (!$this->hasPAN()) {
            throw new \Bav\Bank\Exception\UndefinedAttributeException('pan');
        }
        return $this->pan;
    }

    public function getIbanRule()
    {
        if (!$this->hasIbanRule()) {
            throw new \Bav\Bank\Exception\UndefinedAttributeException('ibanRule');
        }
        return $this->ibanRule;
    }
}
