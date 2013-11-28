<?php

namespace Bav\Validator\De;

use Bav\Validator\Math;

class System98 extends \Bav\Validator\Chain
{

    public function __construct(\Bav\Bank\Bank $bank)
    {
        parent::__construct($bank);
        $this->validators[] = new System01($bank);
        $this->validators[0]->setWeights(array(3, 1, 7));
        $this->validators[0]->setEnd(2);
        
        $this->validators[] = new System32($bank);
    }
    
}