<?php

namespace Bav\Validator\De;

use Bav\Validator\Math;

class SystemD6 extends \Bav\Validator\Chain
{

    public function __construct(\Bav\Bank\Bank $bank)
    {
        parent::__construct($bank);
        $this->validators[] = new System07($bank);
        $this->validators[] = new System03($bank);
        $this->validators[] = new System00($bank);
    }

    
}