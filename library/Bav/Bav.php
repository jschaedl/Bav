<?php

/**
 * Copyright (C) 2012  Dennis Lassiter <dennis@lassiter.de>
 *
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 2 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program; if not, write to the Free Software
 * Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA  02111-1307  USA
 *
 *
 * @author Dennis Lassiter <dennis@lassiter.de>
 * @copyright Copyright (C) 2012 Dennis Lassiter
 */

namespace Bav;

use Bav\Backend\BankDataResolverInterface;

class Bav 
{
	protected $backends = array();
	
	public function setBackend($country, BankDataResolverInterface $backend) {
		$country = ucfirst($country);
		$this->backends [$country] = $backend;
	}
	
	public function getBackend($country) {
		$country = ucfirst($country);
		if (isset($this->backends[$country])) {
			return $this->backends[$country];
		}
		throw new Exception\BackendNotAvailableException();
	}
	
	public function getBank($country, $code) {
		return $this
			->getBackend($country)
			->getBank($code);
	}
	
	public function bankExists($country, $code) {
		return $this
			->getBackend($country)
			->bankExists($code);
	}
}
