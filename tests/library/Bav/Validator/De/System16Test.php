<?php

namespace Bav\Validator\De;

/**
 * Test class for System16.
 * Generated by PHPUnit on 2012-07-05 at 19:22:55.
 */
class System16Test extends SystemTestCase
{

    public function testWithValidAccountReturnsTrue()
    {
        $validAccounts = array('225465411', '834567601');

        foreach ($validAccounts as $account) {
            $validator = new System16($this->bank);
            $this->assertTrue($validator->isValid($account));
        }
    }

    public function testWithInvalidAccountReturnsFalse()
    {
        $validAccounts = array('1000805', '539290859');

        foreach ($validAccounts as $account) {
            $validator = new System16($this->bank);
            $this->assertFalse($validator->isValid($account));
        }
    }

}