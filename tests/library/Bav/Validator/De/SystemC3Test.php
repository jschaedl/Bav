<?php

namespace Bav\Validator\De;

/**
 * Test class for SystemC3.
 * Generated by PHPUnit on 2012-07-05 at 19:22:55.
 */
class SystemC3Test extends SystemTestCase
{

    public function testWithValidAccountReturnsTrue()
    {
        $validAccounts = array('9294182', '4431276', '19919', '9000420530', '9000010006');

        foreach ($validAccounts as $account) {
            $validator = new SystemC3($this->bankId);
            $this->assertTrue($validator->isValid($account));
        }
    }

    public function testWithInvalidAccountReturnsFalse()
    {
        $validAccounts = array('17002', '123451','9000734028', '9000733227');

        foreach ($validAccounts as $account) {
            $validator = new SystemC3($this->bankId);
            $this->assertFalse($validator->isValid($account));
        }
    }

}