<?php

namespace Bav\Validator\De;

use Bav\Validator\Math;

class SystemC7 extends \Bav\Validator\Chain
{

    public function __construct(\Bav\Bank\Bank $bank)
    {
        parent::__construct($bank);
        $this->validators[] = new System63($bank);

        $this->validators[] = new System06($bank);
        $this->validators[1]->setWeights(array(2, 3, 4, 5, 6, 7));
    }
}