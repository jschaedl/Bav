<?php

namespace Bav\Validator\De;

use Bav\Validator\Math;

class SystemC9 extends \Bav\Validator\Chain
{

    public function __construct(\Bav\Bank\Bank $bank)
    {
        parent::__construct($bank);
        $this->validators[] = new System00($bank);
        $this->validators[] = new System07($bank);
    }
    
}