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
 * @author Dennis Lassiter <dennis@lassiter.de>
 * @copyright Copyright (C) 2012 Dennis Lassiter
 */

namespace Bav\Backend;

use Bav\Encoder\EncoderInterface;
use Bav\Backend\Parser\BankDataParser;
use Bav\Backend\Parser\BankDataParserContext;

class BankDataResolver implements BankDataResolverInterface
{
	protected $parser;
    protected $contextCache;
    protected $bankDataCache;
    
    public function __construct($fileName, EncoderInterface $encoder) {
        $this->parser = new BankDataParser($fileName, $encoder);
        $this->contextCache = array();
        $this->bankDataCache = array();
    }

    public function getAllBanks() {
    	
    }
    
    public function getBank($bankId) {
        if (!in_array($bankId, $this->bankDataCache)) {
            $this->bankDataCache[$bankId] = $this->resolveBankData($bankId);
        }
        return $this->bankDataCache[$bankId];
    }
    
    public function bankExists($bankId) {
        try {
            $this->getBank($bankId);
            return true;
        } catch (Exception\BankNotFoundException $e) {
            return false;
        }
    }

    
    private function resolveAgencies($bankId) {
        try {
            $context = $this->defineContextInterval($bankId);
            $agencies = array();
            for ($lineNumer = $context->getStart(); $lineNumer <= $context->getEnd(); $lineNumer++) {
                $agencies[] = $this->parser->readAgency($lineNumer);
            }
            return $agencies;
        } catch (\Exception $e) {
            var_dump($e);
            throw new \LogicException("Start and end should be defined.");
        }
    }
	

    private function findBank($bankId, $offset, $end) {
        if ($end - $offset < 0) {
            throw new Exception\BankNotFoundException("Bank with ID {$bankId} not found");
        }
        $lineNumber = $offset + (int) (($end - $offset) / 2);
        $tempBankId = $this->parser->readBankId($lineNumber);
        if (!isset($this->contextCache[$tempBankId])) {
            $this->contextCache[$tempBankId] = new BankDataParserContext($lineNumber);
        }
        if ($tempBankId < $bankId) {
            return $this->findBank($bankId, $lineNumber + 1, $end);
        } elseif ($tempBankId > $bankId) {
            return $this->findBank($bankId, $offset, $lineNumber - 1);
        } else {
            return $this->parser->readBank($lineNumber);
        }
    }

    private function resolveBankData($bankId) {
		try {
			$this->parser->rewind();
			if (isset($this->contextCache[$bankId])) {
				$bank = $this->findBank($bankId, $this->contextCache[$bankId]->getCurrentLineNumber(), $this->contextCache[$bankId]->getCurrentLineNumber());
			} else {
				$bank = $this->findBank($bankId, 0, $this->parser->getLineCount());
			}
			
			$agencies = $this->resolveAgencies($bankId);
			$bank->setAgencies($agencies);
			return $bank;
		} catch (Parser\Exception\ParseException $e) {
			throw new \Bav\Exception\IoException();
		}
	}

    /**
     *
     * @return Parser\Context\BundesbankBank
     */
    private function defineContextInterval($bankId) {
        if (! isset($this->contextCache[$bankId])) {
            throw new \LogicException("The contextCache object should exist!");
        }
        $context = $this->contextCache[$bankId];
        /**
         * Find start
         */
        if (! $context->isStartDefined()) {
            for ($start = $context->getCurrentLineNumber() - 1; $start >= 0; $start--) {
                if ($this->parser->readBankId($start) != $bankId) {
                    break;
                }
            }
            $context->setStart($start + 1);
        }
        /**
         * Find end
         */
        if (! $context->isEndDefined()) {
            for ($end = $context->getCurrentLineNumber() + 1; $end <= $this->parser->getLineCount(); $end++) {
                if ($this->parser->readBankId($end) != $bankId) {
                    break;
                }
            }
            $context->setEnd($end - 1);
        }
        return $context;
    }
}