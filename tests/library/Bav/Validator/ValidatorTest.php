<?php
namespace Bav\Validator;

use Bav\Validator\ValidatorFactory;

class ValidatorTest extends \PHPUnit_Framework_TestCase
{
	/**
	 * @dataProvider validAccountsDataProvider
	 */
	public function testValidAccounts($validationType, $bankId)
	{
		$validator = ValidatorFactory::create($validationType, $bankId);
		$this->assertTrue($validator->isValid($bankId));
	}
	
	/**
	 * @dataProvider invalidAccountsDataProvider
	 */
	public function testInvalidAccounts($validationType, $bankId)
	{
		$validator = ValidatorFactory::create($validationType, $bankId);
		$this->assertFalse($validator->isValid($bankId));
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