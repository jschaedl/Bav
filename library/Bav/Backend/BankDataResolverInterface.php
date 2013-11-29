<?php
namespace Bav\Backend;

interface BankDataResolverInterface
{
	public function getAllBanks();

	public function getBank($bankID);

	public function bankExists($bankID);
}
