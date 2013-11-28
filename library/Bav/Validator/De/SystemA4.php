<?php

namespace Bav\Validator\De;

use Bav\Validator\Math;

class SystemA4 extends \Bav\Validator\Chain
{

    public function __construct(\Bav\Bank\Bank $bank)
    {
        parent::__construct($bank);
        $this->validators[] = new System06($bank);
        $this->validators[0]->setWeights(array(2, 3, 4, 5, 6, 7, 0, 0, 0));
        $this->validators[0]->setEnd(3);
        
        $this->validators[] = new SystemA4b($bank);
        
        $this->validators[] = new System06($bank);
        $this->validators[2]->setWeights(array(2, 3, 4, 5, 6, 0, 0, 0, 0));
        $this->validators[2]->setEnd(4);
        
        $this->validators[] = new System93($bank);
    }
    
    
    /**
     * Decide if you really want to use this validator
     *
     * @return bool
     */
    protected function useValidator(\Bav\Validator\Base $validator)
    {
        if (substr($this->account, 2, 2) == '99') {
            return $validator === $this->validators[2]
                || $validator === $this->validators[3];
        
        } else {
            return  $validator !== $this->validators[2];
            
        }
    }
    
}