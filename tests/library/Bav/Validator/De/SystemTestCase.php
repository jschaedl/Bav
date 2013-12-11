<?php
namespace Bav\Validator\De;

use Bav\Bank\Bank;

class SystemTestCase extends \PHPUnit_Framework_TestCase
{
    protected $bank;

    public function setUp()
    {
        $this->bank = new Bank('12345678', '00');
    }
}