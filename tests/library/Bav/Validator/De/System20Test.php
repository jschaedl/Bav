<?php

namespace Bav\Validator\De;

/**
 * Test class for System20.
 * Generated by PHPUnit on 2012-07-05 at 19:22:55.
 */
class System20Test extends SystemTestCase
{

    public function testWithValidAccountReturnsTrue()
    {
        $validAccounts = array('0240334000', '0200520016');

        foreach ($validAccounts as $account) {
            $validator = new System20($this->bank);
            $this->assertTrue($validator->isValid($account));
        }
    }

    public function testWithInvalidAccountReturnsFalse()
    {
        $validAccounts = array('1000805', '539290859');

        foreach ($validAccounts as $account) {
            $validator = new System20($this->bank);
            $this->assertFalse($validator->isValid($account));
        }
    }

}