<?php

namespace Bav\Validator\De;

use Bav\Validator\Math;

class SystemC1 extends \Bav\Validator\Base
{

    protected $validator;
    protected $mode1;
    protected $mode2;



    public function __construct(\Bav\Bank\Bank $bank)
    {
        parent::__construct($bank);
        $this->mode1 = new System17($bank);
        $this->mode1->setWeights(array(1, 2));
        
        $this->mode2 = new SystemC1b($bank);
    }
    
    protected function validate()
    {
        $this->validator = $this->account{0} != '5'
                         ? $this->mode1
                         : $this->mode2;
    }
    /**
     * @return bool
     */
    protected function getResult()
    {
        return $this->validator->isValid($this->account);
    }
    
    
}