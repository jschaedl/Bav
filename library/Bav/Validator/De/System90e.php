<?php

namespace Bav\Validator\De;

use Bav\Validator\Math;

class System90e extends \Bav\Validator\IterationWeighted
{

    public function __construct(\Bav\Bank\Bank $bank)
    {
        parent::__construct($bank);
        $this->setWeights(array(2, 1));
        $this->setEnd(4);
    }
    
    protected function iterationStep()
    {
        $this->accumulator += $this->number * $this->getWeight();
    }


    protected function getResult()
    {
        $result = (10 - $this->accumulator % 10) % 10;
        return (string)$result === $this->getCheckNumber();
    }
    
}