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
 * @package Backend
 * @subpackage Parser
 * @author Dennis Lassiter <dennis@lassiter.de>
 * @copyright Copyright (C) 2012 Dennis Lassiter
 */

namespace Bav\Backend\Parser;

use Bav\Exception as BavException;
use Bav\Encoder\EncoderFactory;
use Bav\Bank\Bank;
use Bav\Encoder\EncoderInterface;

class BankDataParser
{
    const FILE_ENCODING = 'ISO-8859-15';
    const BANKID_OFFSET = 0;
    const BANKID_LENGTH = 8;
    const ISMAIN_OFFSET = 8;
    const ISMAIN_LENGTH = 1;
    const NAME_OFFSET = 9;
    const NAME_LENGTH = 58;
    const POSTCODE_OFFSET = 67;
    const POSTCODE_LENGTH = 5;
    const CITY_OFFSET = 72;
    const CITY_LENGTH = 35;
    const SHORTTERM_OFFSET = 107;
    const SHORTTERM_LENGTH = 27;
    const PAN_OFFSET = 134;
    const PAN_LENGTH = 5;
    const BIC_OFFSET = 139;
    const BIC_LENGTH = 11;
    const TYPE_OFFSET = 150;
    const TYPE_LENGTH = 2;
    const ID_OFFSET = 152;
    const ID_LENGTH = 6;
    
    private $fp;
    private $fileName = '';
    private $lines = 0;
    private $lineLength = 0;
    
    protected $encoder;

    public function __construct($fileName, EncoderInterface $encoder) {
        $this->fileName = $fileName;
        $this->encoder = $encoder;
        $this->init();
    }

    private function init() {
        $this->fp = @fopen($this->fileName, 'r');
        if (! is_resource($this->fp)) {
            if (! file_exists($this->fileName)) {
                throw new BavException\FileNotFoundException("File {$this->fileName} not found.");
            } else {
                throw new BavException\IoException("Failed to open stream {$this->fileName}");
            }
        }    
    }

    private function getLineLength() {
    	if ($this->lineLength == 0) {
    		$dummyLine = fgets($this->fp, 1024);
    		if (!$dummyLine) {
    			throw new BavException\IoException("Failed to open stream {$this->fileName}");
    		}
    		$this->lineLength = strlen($dummyLine);
    	}
    	return $this->lineLength;
    }
    
    public function getLines() {
    	if ($this->lines == 0) {
    		clearstatcache(); // filesize() seems to be 0 sometimes

   			$filesize = filesize($this->fileName);
  			if (! $filesize) {
   				throw new BavException\IoException("Could not read filesize for {$this->fileName}");
   			}
   			$this->lines = floor(($filesize - 1) / $this->getLineLength());  			 
    	}
        return $this->lines;
    }

    public function rewind() {
        if (rewind($this->getFileHandle()) === 0) {
            throw new BavException\IoException();
        }
    }

    public function seekLine($line, $offset = 0) {
        if (fseek($this->getFileHandle(), $line * $this->getLineLength() + $offset) === - 1) {
            throw new BavException\IoException();
        }
    }

    public function readLine($line) {
        $this->seekLine($line);
        return $this->encoder->convert(fread($this->getFileHandle(), $this->getLineLength()), self::FILE_ENCODING);
    }

    public function getBankId($line) {
        $this->seekLine($line, self::BANKID_OFFSET);
        return $this->encoder->convert(fread($this->getFileHandle(), self::BANKID_LENGTH), self::FILE_ENCODING);
    }

    public function getFileHandle() {
        return $this->fp;
    }

    public function __destruct() {
        if (is_resource($this->fp)) {
            fclose($this->fp);
        }
    }

    public function getBank($line) {
        $this->checkValidLineLength($line);
        $type = $this->encoder->substr($line, self::TYPE_OFFSET, self::TYPE_LENGTH);
        $bankId = $this->encoder->substr($line, self::BANKID_OFFSET, self::BANKID_LENGTH);
        
        return new Bank($bankId, 'De\\System' . $type);
    }

    public function getAgency($line) {
        $this->checkValidLineLength($line);
        $id = trim($this->encoder->substr($line, self::ID_OFFSET, self::ID_LENGTH));
        $name = trim($this->encoder->substr($line, self::NAME_OFFSET, self::NAME_LENGTH));
        $shortTerm = trim($this->encoder->substr($line, self::SHORTTERM_OFFSET, self::SHORTTERM_LENGTH));
        $city = trim($this->encoder->substr($line, self::CITY_OFFSET, self::CITY_LENGTH));
        $postcode = $this->encoder->substr($line, self::POSTCODE_OFFSET, self::POSTCODE_LENGTH);
        $bic = trim($this->encoder->substr($line, self::BIC_OFFSET, self::BIC_LENGTH));
        $pan = trim($this->encoder->substr($line, self::PAN_OFFSET, self::PAN_LENGTH));
        
        $mainAgency = $this->isMainAgency($line);
        
        return new \Bav\Bank\Agency($id, $name, $shortTerm, $city, $postcode, $bic, $pan, $mainAgency);
    }

    public function isMainAgency($line) {
        $this->checkValidLineLength($line);
        return $this->encoder->substr($line, self::ISMAIN_OFFSET, 1) === '1';
    }
    private function checkValidLineLength($line) {
        if ($this->encoder->strlen($line) < self::TYPE_OFFSET + self::TYPE_LENGTH) {
            throw new Exception\ParseException("Invalid line length in Line {$line}.");
        }
    }
    

    public function getFileName() {
        return $this->fileName;
    }
}