<?php

namespace Bav\Validator\De;

use Bav\Validator\Math;

class System87 extends \Bav\Validator\Chain
{

    
    public function __construct(\Bav\Bank\Bank $bank)
    {
        parent::__construct($bank);
        $this->defaultValidators[] = new System87a($bank);
        
        $this->defaultValidators[] = new System33($bank);
        $this->defaultValidators[1]->setWeights(array(2, 3, 4, 5, 6));
        $this->defaultValidators[1]->setEnd(4);
        
        $this->defaultValidators[] = new System87c($bank);
        
        $this->exceptionValidators = System51::getExceptionValidators($bank);
    }

    protected function init($account)
    {
        parent::init($account);
        
        $this->validators = $this->account{2} == 9
                          ? $this->exceptionValidators
                          : $this->defaultValidators;
    }
}