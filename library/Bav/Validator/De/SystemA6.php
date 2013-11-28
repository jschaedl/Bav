<?php

namespace Bav\Validator\De;

use Bav\Validator\Math;

class SystemA6 extends \Bav\Validator\Base
{

    protected $validator;
    protected $mode1;
    protected $mode2;

    public function __construct(\Bav\Bank\Bank $bank)
    {
        parent::__construct($bank);
        $this->mode1 = new System00($bank);
        $this->mode1->setWeights(array(2, 1));
        
        $this->mode2 = new System01($bank);
        $this->mode2->setWeights(array(3, 7, 1));
    }
    
    protected function validate()
    {
        $this->validator = $this->account{1} === '8'
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