<?php

namespace Bav\Validator\De;

use Bav\Validator\Math;

class System82 extends \Bav\Validator\Base
{
    
    protected $validator;
    protected $mode1;
    protected $mode2;
    
    public function __construct(\Bav\Bank\Bank $bank)
    {
        parent::__construct($bank);
        $this->mode1 = new System33($bank);
        $this->mode1->setWeights(array(2, 3, 4, 5, 6));
        
        $this->mode2 = new System10($bank);
    }
    
    protected function validate()
    {
        $this->validator = substr($this->account, 2 ,2) == 99
                         ? $this->mode2
                         : $this->mode1;
    }

    protected function getResult()
    {
        return $this->validator->isValid($this->account);
    }
}