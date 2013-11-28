<?php

namespace Bav\Validator\De;

use Bav\Validator\Math;
use Bav\Bank\Bank;

class SystemD9 extends \Bav\Validator\Chain
{

    public function __construct(Bank $bank)
    {
        parent::__construct($bank);
        $this->validators[] = new System00($bank);
        $this->validators[] = new System10($bank);
        $this->validators[] = new System18($bank);
    }
    
}