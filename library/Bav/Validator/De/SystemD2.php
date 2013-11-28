<?php

namespace Bav\Validator\De;

use Bav\Validator\Math;

class SystemD2 extends \Bav\Validator\Chain
{
    
    protected $doNormalization = false;
    
    public function __construct(\Bav\Bank\Bank $bank)
    {
        parent::__construct($bank);
        $this->validators[] = new System95($bank);
        $this->validators[] = new System00($bank);
        $this->validators[] = new System68($bank);
    }
    
    
}