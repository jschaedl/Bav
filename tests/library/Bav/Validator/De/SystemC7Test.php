<?php

namespace Bav\Validator\De;

/**
 * Test class for SystemC7.
 * Generated by PHPUnit on 2012-07-05 at 19:22:55.
 */
class SystemC7Test extends SystemTestCase
{

    public function testWithValidAccountReturnsTrue()
    {
        $validAccounts = array('3500022', '38150900', '94012341');

        foreach ($validAccounts as $account) {
            $validator = new SystemC7($this->bank);
            $this->assertTrue($validator->isValid($account));
        }
    }

    public function testWithInvalidAccountReturnsFalse()
    {
        $validAccounts = array('1234517892', '987614325');

        foreach ($validAccounts as $account) {
            $validator = new SystemC7($this->bank);
            $this->assertFalse($validator->isValid($account));
        }
    }

}