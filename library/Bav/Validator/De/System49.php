<?php

namespace Bav\Validator\De;

use Bav\Validator\Math;

class System49 extends \Bav\Validator\Chain
{
    
    public function __construct(\Bav\Bank\Bank $bank)
    {
        parent::__construct($bank);
        $this->validators = array(
            new System00($bank),
            new System01($bank)
        );
    }
    
}