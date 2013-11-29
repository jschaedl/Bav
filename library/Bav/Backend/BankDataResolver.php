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

use Bav\Backend\Parser\BankDataParser;
use Bav\Encoder\EncoderInterface;
use Bav\Backend\Parser\Context\BankDataParserContext;

class BankDataResolver implements BankDataResolverInterface
{
    protected $content = array();
    protected $contextCache = array();
    protected $bankCache = array();
    protected $parser;

    public function __construct($fileName, EncoderInterface $encoder) {
        $this->parser = new BankDataParser($fileName, $encoder);
    }

    public function getAllBanks() {
    	
    }
    
    public function getBank($bankId) {
        if (!in_array($bankId, $this->bankCache)) {
            $this->bankCache[$bankId] = $this->resolveBank($bankId);
        }
        return $this->bankCache[$bankId];
    }
    
    public function bankExists($bankID) {
        try {
            $this->getBank($bankID);
            return true;
        } catch (Exception\BankNotFoundException $e) {
            return false;
        }
    }

    
    protected function getAgencies($bankId) {
        try {
            $context = $this->defineContextInterval($bankId);
            $agencies = array();
            for ($line = $context->getStart(); $line <= $context->getEnd(); $line++) {
                $content = $this->parser->readLine($line);
                $agencies[] = $this->parser->getAgency($content);
            }
            return $agencies;
        } catch (\Exception $e) {
            var_dump($e);
            throw new \LogicException("Start and end should be defined.");
        }
    }
	

    protected function findBank($bankID, $offset, $end) {
        if ($end - $offset < 0) {
            throw new Exception\BankNotFoundException("Bank with ID {$bankID} not found");
        }
        
        $line = $offset + (int) (($end - $offset) / 2);
        $blz = $this->parser->getBankId($line);
        
        /**
         * This handling is bad, as it may double the work
         */
        if ($blz == '00000000') {
            try {
                return $this->findBank($bankID, $offset, $line - 1);
            } catch (Exception\BankNotFoundException $e) {
                return $this->findBank($bankID, $line + 1, $end);
            }
        } elseif (! isset($this->contextCache[$blz])) {
            $this->contextCache[$blz] = new BankDataParserContext($line);
        }
        
        if ($blz < $bankID) {
            return $this->findBank($bankID, $line + 1, $end);
        } elseif ($blz > $bankID) {
            return $this->findBank($bankID, $offset, $line - 1);
        } else {
            return $this->parser->getBank($this->parser->readLine($line));
        }
    }

    /**
     *
     * @throws BAV_DataBackendException_IO
     * @throws BAV_DataBackendException_BankNotFound
     * @param String $bankId            
     * @see BAV_DataBackend::getNewBank()
     * @return BAV_Bank
     */
    protected function resolveBank($bankId) {
        try {
            $this->parser->rewind();
            /**
             * TODO Binary Search is also possible on $this->contextCache,
             * to reduce the interval of $offset and $end;
             */
            /* @var $bank \Bav\Bank */
            if (isset($this->contextCache[$bankId])) {
                $bank = $this->findBank($bankId, $this->contextCache[$bankId]->getLine(), $this->contextCache[$bankId]->getLine());
            } else {
                $bank = $this->findBank($bankId, 0, $this->parser->getLines());
                $agencies = $this->getAgencies($bankId);
                $bank->setAgencies($agencies);
            }
            
            return $bank;
        } catch (Parser\Exception\ParseException $e) {
            throw new \Bav\Exception\IoException();
        }
    }

    /**
     *
     * @return Parser\Context\BundesbankBank
     */
    protected function defineContextInterval($bankId) {
        if (! isset($this->contextCache[$bankId])) {
            throw new \LogicException("The contextCache object should exist!");
        }
        $context = $this->contextCache[$bankId];
        /**
         * Find start
         */
        if (! $context->isStartDefined()) {
            for ($start = $context->getLine() - 1; $start >= 0; $start--) {
                if ($this->parser->getBankId($start) != $bankId) {
                    break;
                }
            }
            $context->setStart($start + 1);
        }
        /**
         * Find end
         */
        if (! $context->isEndDefined()) {
            for ($end = $context->getLine() + 1; $end <= $this->parser->getLines(); $end++) {
                if ($this->parser->getBankId($end) != $bankId) {
                    break;
                }
            }
            $context->setEnd($end - 1);
        }
        return $context;
    }
}