<?php
namespace Bav;

use Bav\Backend\BankDataResolverInterface;
use Bav\Encoder\EncoderFactory;
use Bav\Backend\Parser\BankDataParser;
use Bav\Backend\BankDataResolver;

class Bav
{
	const DEFAULT_LOCALE = 'DE';
	const DEFAULT_ENCODING = 'ISO-8859-15';
	
	private $bankDataFile;
	private $backends = array();

	public function __construct() {
		$this->bankDataFile = __DIR__ . 
			DIRECTORY_SEPARATOR . '..' . 
			DIRECTORY_SEPARATOR . '..' . 
			DIRECTORY_SEPARATOR . 'tests/data/blz_2013_12_09_txt.txt';
	}
	
	public static function DE() {
		$bav = new Bav();
		return $bav->createDefault();
	}

	public function getBank($bankCode, $locale = Bav::DEFAULT_LOCALE) {
		return $this->getBackend($locale)->getBank($bankCode);
	}

	public function bankExists($bankCode, $locale = Bav::DEFAULT_LOCALE) {
		return $this->getBackend($locale)->bankExists($bankCode);
	}

	public function setBackend(BankDataResolverInterface $bankDataResolver, $locale = Bav::DEFAULT_LOCALE) {
		$this->backends[strtoupper($locale)] = $bankDataResolver;
	}

	public function getBackend($locale) {
		$locale = strtoupper($locale);
		if (isset($this->backends[$locale])) {
			return $this->backends[$locale];
		}
		throw new Exception\BackendNotAvailableException();
	}

	private function createDefault() {
		$encoder = EncoderFactory::create(Bav::DEFAULT_ENCODING);
		$parser = new BankDataParser($this->bankDataFile);
		$parser->setEncoder($encoder);
		$resolver = new BankDataResolver($parser);
		$bav = new Bav();
		$bav->setBackend($resolver);
		return $bav;
	}
}
