<?php
/**
 * Sie5Sdk    PHP SDK for Sie5 export/import format
 *            based on the Sie5 (http://www.sie.se/sie5.xsd) schema
 *
 * This file is a part of Sie5Sdk.
 *
 * @author    Kjell-Inge Gustafsson, kigkonsult <ical@kigkonsult.se>
 * @copyright 2019-2021 Kjell-Inge Gustafsson, kigkonsult, All rights reserved
 * @link      https://kigkonsult.se
 * @license   Subject matter of licence is the software Sie5Sdk.
 *            The above copyright, link and package notices, this licence
 *            notice shall be included in all copies or substantial portions
 *            of the Sie5Sdk.
 *
 *            Sie5Sdk is free software: you can redistribute it and/or modify
 *            it under the terms of the GNU Lesser General Public License as
 *            published by the Free Software Foundation, either version 3 of
 *            the License, or (at your option) any later version.
 *
 *            Sie5Sdk is distributed in the hope that it will be useful,
 *            but WITHOUT ANY WARRANTY; without even the implied warranty of
 *            MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 *            GNU Lesser General Public License for more details.
 *
 *            You should have received a copy of the GNU Lesser General Public License
 *            along with Sie5Sdk. If not, see <https://www.gnu.org/licenses/>.
 */
declare( strict_types = 1 );
namespace Kigkonsult\Sie5Sdk\XMLParse;

use Exception;
use Kigkonsult\Sie5Sdk\Dto\SupplierInvoicesType;
use XMLReader;

use function sprintf;

class SupplierInvoicesTypeParser extends Sie5ParserBase
{
    /**
     * Parse
     *
     * @return SupplierInvoicesType
     * @throws Exception
     */
    public function parse() : SupplierInvoicesType
    {
        $supplierInvoicesType = SupplierInvoicesType::factory()->setXMLattributes( $this->reader );
        $this->logger->debug(
            sprintf( self::$FMTstartNode, __METHOD__, self::$nodeTypes[$this->reader->nodeType], $this->reader->localName )
        );
        if( $this->reader->hasAttributes ) {
            while( $this->reader->moveToNextAttribute()) {
                $this->logger->debug(
                    sprintf( self::$FMTattrFound, __METHOD__, $this->reader->name, $this->reader->value )
                );
                switch( $this->reader->name ) {
                    case self::PRIMARYACCOUNTID :
                        $supplierInvoicesType->setPrimaryAccountId( $this->reader->value );
                        break;
                    case self::NAME :
                        $supplierInvoicesType->setName( $this->reader->value );
                        break;
                } // end switch
            } // end while
            $this->reader->moveToElement();
        } // end if
        if( $this->reader->isEmptyElement ) {
            return $supplierInvoicesType;
        }
        $headElement = $this->reader->localName;
        $parser      = new SupplierInvoiceTypeParser( $this->reader );
        while( @$this->reader->read()) {
            if( XMLReader::SIGNIFICANT_WHITESPACE !== $this->reader->nodeType ) {
                $this->logger->debug(
                    sprintf( self::$FMTreadNode, __METHOD__, self::$nodeTypes[$this->reader->nodeType], $this->reader->localName )
                );
            }
            switch( true ) {
                case ( XMLReader::END_ELEMENT === $this->reader->nodeType ) :
                    if( $headElement === $this->reader->localName ) {
                        break 2;
                    }
                    break;
                case ( XMLReader::ELEMENT !== $this->reader->nodeType ) :
                    break;
                case ( self::SECONDARYACCOUNTREF === $this->reader->localName ) :
                    if( $this->reader->hasAttributes ) {
                        while( $this->reader->moveToNextAttribute()) {
                            $this->logger->debug(
                                sprintf( self::$FMTattrFound, __METHOD__, $this->reader->name, $this->reader->value )
                            );
                            if( self::ACCOUNTID === $this->reader->name ) {
                                $supplierInvoicesType->addSecondaryAccountRef( $this->reader->value );
                            }
                        }
                        $this->reader->moveToElement();
                    } // end if
                    break;
                case ( self::SUPPLIERINVOICE === $this->reader->localName ) :
                    $supplierInvoicesType->addSupplierInvoice( $parser->parse());
                    break;
            } // end switch
        } // end while
        return $supplierInvoicesType;
    }
}
