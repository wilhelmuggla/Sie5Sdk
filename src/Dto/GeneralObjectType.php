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

use function array_keys;

class GeneralObjectType extends SubdividedAccountObjectType
{
    /**
     * Return bool true is instance is valid
     *
     * @param array|null $outSide
     * @return bool
     */
    public function isValid( ? array & $outSide = [] ) : bool
    {
        $local  = [];
        $inside = [];
        if( ! empty( $this->balances )) {
            foreach( array_keys( $this->balances ) as $ix ) {
                $inside[$ix] = [];
                if( $this->balances[$ix]->isValid( $inside[$ix] )) {
                    unset( $inside[$ix] );
                }
            } // end foreach
            if( ! empty( $inside )) {
                $key         = self::getClassPropStr( self::class, self::BALANCES );
                $local[$key] = $inside;
                $inside      = [];
            } // end if
        } // end if
        if( empty( $this->originalAmount )) {
            $local[] = self::errMissing(self::class, self::ORIGINALAMOUNT );
        }
        elseif( ! $this->originalAmount->isValid( $inside )) {
            $local[] = $inside;
        }
        if( null === $this->id ) {
            $local[] = self::errMissing(self::class, self::ID );
        }
        if( ! empty( $local )) {
            $outSide[] = $local;
            return false;
        }
        return true;
    }
}
