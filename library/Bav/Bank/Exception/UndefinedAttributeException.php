<?php
namespace Bav\Bank\Exception;

class UndefinedAttributeException extends \Exception
{
    public $undefinedAttribute;

    public function __construct($attribute)
    {
        parent::__construct();
        
        $this->undefinedAttribute = $attribute;
    }
}