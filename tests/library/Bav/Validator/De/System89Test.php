<?php

namespace Bav\Validator\De;

/**
 * Test class for System89.
 * Generated by PHPUnit on 2012-07-05 at 19:22:55.
 */
class System89Test extends SystemTestCase
{

    public function testWithValidAccountReturnsTrue()
    {
        $validAccounts = array('1098506', '32028008', '218433000');

        foreach ($validAccounts as $account) {
            $validator = new System89($this->bank);
            $this->assertTrue($validator->isValid($account));
        }
    }

    public function testWithInvalidAccountReturnsFalse()
    {
        $validAccounts = array('223456600', '45555555');

        foreach ($validAccounts as $account) {
            $validator = new System89($this->bank);
            $this->assertFalse($validator->isValid($account));
        }
    }

}