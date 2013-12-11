<?php

namespace Bav\Validator\De;

/**
 * Test class for System51.
 * Generated by PHPUnit on 2012-07-05 at 19:22:55.
 */
class System51Test extends SystemTestCase
{

    public function testWithValidAccountReturnsTrue()
    {
        $validAccounts = array('0001156071', '0000156078', '0000156071', '0199100002', '0199100004');

        foreach ($validAccounts as $account) {
            $validator = new System51($this->bank);
            $this->assertTrue($validator->isValid($account));
        }
    }

    public function testWithInvalidAccountReturnsFalse()
    {
        $validAccounts = array('0099345678', '0099100110');

        foreach ($validAccounts as $account) {
            $validator = new System51($this->bank);
            $this->assertFalse($validator->isValid($account));
        }
    }

}