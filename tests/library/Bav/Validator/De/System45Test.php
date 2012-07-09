<?php

namespace Bav\Validator\De;

/**
 * Test class for System45.
 * Generated by PHPUnit on 2012-07-05 at 19:22:55.
 */
class System45Test extends \Bav\Test\SystemTestCase
{

    public function testWithValidAccountReturnsTrue()
    {
        $validAccounts = array('3545343232', '4013410024', '0994681254', '1000199999');

        foreach ($validAccounts as $account) {
            $validator = new System45($this->bank);
            $this->assertTrue($validator->isValid($account));
        }
    }

    public function testWithInvalidAccountReturnsFalse()
    {
        $validAccounts = array('1234567890', '2345678901');

        foreach ($validAccounts as $account) {
            $validator = new System45($this->bank);
            $this->assertFalse($validator->isValid($account));
        }
    }

}