<?php

namespace Bav\Validator\De;

use Bav\Validator\Math;

class SystemC2 extends \Bav\Validator\Chain
{
    
    public function __construct(\Bav\Bank\Bank $bank)
    {
        parent::__construct($bank);
        $this->validators[] = new System22($bank);
        $this->validators[0]->setWeights(array(3, 1));
        
        $this->validators[] = new System00($bank);
        $this->validators[1]->setWeights(array(2, 1));
    }
    
    
}