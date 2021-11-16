<?php
/**
 * SieSdk     PHP SDK for Sie5 export/import format
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
namespace Kigkonsult\Sie5Sdk\Dto;

use TypeError;

use function array_keys;

class GeneralSubdividedAccountType extends BaseSubdividedAccountType
{
    /**
     * @var GeneralObjectType[]
     */
    private array $generalObject = [];

    /**
     * Return bool true is instance is valid
     *
     * @param array|null $outSide
     * @return bool
     */
    public function isValid( ? array & $outSide = [] ) : bool
    {
        $local = [];
        if( null === $this->primaryAccountId ) {
            $local[] = self::errMissing(self::class, self::PRIMARYACCOUNTID );
        }
        $inside = [];
        foreach( array_keys( $this->generalObject ) as $ix ) { // element ix
            $inside[$ix] = [];
            if( $this->generalObject[$ix]->isValid( $inside[$ix] )) {
                unset( $inside[$ix] );
            }
        } // end foreach
        if( ! empty( $local )) {
            $outSide[] = $local;
            return false;
        }
        return true;
    }

    /**
     * Add single GeneralObjectType
     *
     * @param GeneralObjectType $generalObject
     * @return static
     */
    public function addGeneralObject( GeneralObjectType $generalObject ) : self
    {
        $this->generalObject[] = $generalObject;
        return $this;
    }

    /**
     * @return GeneralObjectType[]
     */
    public function getGeneralObject() : array
    {
        return $this->generalObject;
    }

    /**
     * Set GeneralObjectTypes, array
     *
     * @param GeneralObjectType[] $generalObject
     * @return static
     * @throws TypeError
     */
    public function setGeneralObject( array $generalObject ) : self
    {
        foreach( $generalObject as $value ) {
            $this->addGeneralObject( $value );
        } // end foreach
        return $this;
    }
}
