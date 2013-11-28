<?php

namespace Bav\Validator\De;

use Bav\Validator\Math;

class SystemA7 extends \Bav\Validator\Chain
{

    public function __construct(\Bav\Bank\Bank $bank)
    {
        parent::__construct($bank);
        $this->validators[] = new System00($bank);
        $this->validators[0]->setWeights(array(2, 1));
        
        $this->validators[] = new System03($bank);
    }
    
}