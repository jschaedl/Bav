<?php

/**
 * Copyright (C) 2012 Dennis Lassiter <dennis@lassiter.de>
 *
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 2 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program; if not, write to the Free Software
 * Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA 02111-1307 USA
 *
 *
 * @author Dennis Lassiter <dennis@lassiter.de>
 * @copyright Copyright (C) 2012 Dennis Lassiter
 */
namespace Bav;

use Bav\Backend\BankDataResolverInterface;

class Bav
{
	const DEFAULT_ENCODING = 'ISO-8859-15';
	const DEFAULT_BANKDATA_FILE = '../data/blz_2013-12-09_txt.txt';
	
	private $backends = array();
	
	public static function createDefault() {
		$encoder = EncoderFactory::create(DEFAULT_ENCODING);
		$parser = new BankDataParser(DEFAULT_BANKDATA_FILE);
		$parser->setEncoder($encoder);
		$resolver = new BankDataResolver($parser);
		$bav = new Bav();
		$bav->setBackend('de', $resolver);
		return $bav;
	}
	
	public function setBackend($locale, BankDataResolverInterface $bankDataResolver) {
		$this->backends[strtoupper($locale)] = $bankDataResolver;
	}

	public function getBackend($locale) {
		$locale = strtoupper($locale);
		if (isset($this->backends[$locale])) {
			return $this->backends[$locale];
		}
		throw new Exception\BackendNotAvailableException();
	}

	public function getBank($locale, $bankCode) {
		return $this
			->getBackend($locale)
			->getBank($bankCode);
	}

	public function bankExists($locale, $bankCode) {
		return $this
			->getBackend($locale)
			->bankExists($bankCode);
	}
}
