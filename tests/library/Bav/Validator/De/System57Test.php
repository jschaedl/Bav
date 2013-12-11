<?php

namespace Bav\Validator\De;

/**
 * Test class for System57.
 * Generated by PHPUnit on 2012-07-05 at 19:22:55.
 */
class System57Test extends SystemTestCase
{

    public function testWithValidAccountReturnsTrue()
    {
        $validAccounts = explode(
            ',',
            '7500021766,9400001734,7800028282,8100244186,3251080371,3891234567,7777778800,5001050352,5045090090,1909700805,9322111030'
        );

        foreach ($validAccounts as $account) {
            $validator = new System57($this->bank);
            $this->assertTrue($validator->isValid($account));
        }
    }

    public function testWithInvalidAccountReturnsFalse()
    {
        $validAccounts = array('5302707782', '6412121212');

        foreach ($validAccounts as $account) {
            $validator = new System57($this->bank);
            $this->assertFalse($validator->isValid($account));
        }
    }

}