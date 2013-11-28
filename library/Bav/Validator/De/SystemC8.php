<?php

namespace Bav\Validator\De;

use Bav\Validator\Math;

class SystemC8 extends \Bav\Validator\Chain
{

    public function __construct(\Bav\Bank\Bank $bank)
    {
        parent::__construct($bank);
        $this->validators[] = new System00($bank);
        $this->validators[] = new System04($bank);
        $this->validators[] = new System07($bank);
    }
    
    
}