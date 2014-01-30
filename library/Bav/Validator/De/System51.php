<?php

namespace Bav\Validator\De;

use Bav\Validator\Math;

class System51 extends \Bav\Validator\Chain
{
    
    protected $defaultValidators = array();
    protected $exceptionValidators = array();
    protected $validatorD;
    
    public function __construct($bankId)
    {
        parent::__construct($bankId);
        $this->validatorD = new System33($bankId);
        
        $this->defaultValidators[] = new System06($bankId);
        $this->defaultValidators[] = new System33($bankId);
        $this->defaultValidators[] = new System00($bankId);
        $this->defaultValidators[] = $this->validatorD;
        
        $this->defaultValidators[0]->setWeights(array(2, 3, 4, 5, 6, 7));
        $this->defaultValidators[0]->setEnd(3);
        
        $this->defaultValidators[1]->setWeights(array(2, 3, 4, 5, 6));
        $this->defaultValidators[1]->setEnd(4);

        $this->defaultValidators[2]->setWeights(array(2, 1));
        $this->defaultValidators[2]->setEnd(3);
        $this->defaultValidators[2]->setModulo(10);
        
        $this->defaultValidators[3]->setWeights(array(2, 3, 4, 5, 6));
        $this->defaultValidators[3]->setEnd(4);
        $this->defaultValidators[3]->setModulo(7);
        
        $this->exceptionValidators = self::getExceptionValidators($bankId);
    }
    
    /**
     * @return array
     */
    public static function getExceptionValidators($bankId)
    {
        $exceptionValidators = array();
        $exceptionValidators[] = new System51x($bankId);
        $exceptionValidators[] = new System51x($bankId);
            
        $exceptionValidators[1]->setWeights(array(2, 3, 4, 5, 6, 7, 8, 9, 10));
        $exceptionValidators[1]->setEnd(0);
        
        return $exceptionValidators;
    }
    
    protected function init($account)
    {
        parent::init($account);
        
        $this->validators = $this->account{2} == 9
                          ? $this->exceptionValidators
                          : $this->defaultValidators;
    }
    
    
    protected function continueValidation(\Bav\Validator\Base $validator)
    {
        if ($validator !== $this->validatorD) {
            return true;
        
        }
        switch ($this->account{9}) {
            case 7: case 8: case 9:
                return false;
        
            default:
                return true;
        
        }
    }
}