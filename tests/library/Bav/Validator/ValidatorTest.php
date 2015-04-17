<?php
namespace Bav\Validator;

use Bav\Validator\ValidatorFactory;

class ValidatorTest extends \PHPUnit_Framework_TestCase
{
	/**
	 * @dataProvider validAccountsDataProvider
	 */
	public function testValidAccounts($validationType, $account, $bankId)
	{
		$validator = ValidatorFactory::create($validationType, $bankId);
		$this->assertTrue($validator->isValid($account));
	}
	
	/**
	 * @dataProvider invalidAccountsDataProvider
	 */
	public function testInvalidAccounts($validationType, $account, $bankId)
	{
		$validator = ValidatorFactory::create($validationType, $bankId);
		$this->assertFalse($validator->isValid($account));
	}
	
	
	public function validAccountsDataProvider()
	{
		return new CsvFileIterator('./tests/data/validaccounts.csv');
	}
	
	public function invalidAccountsDataProvider()
	{
		return new CsvFileIterator('./tests/data/invalidaccounts.csv');
	}
}