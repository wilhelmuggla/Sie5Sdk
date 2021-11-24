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
namespace Kigkonsult\Sie5Sdk\DtoLoader;

use Kigkonsult\Sie5Sdk\Dto\SupplierInvoicesType as Dto;
use Faker;

use function array_flip;
use function array_rand;

class SupplierInvoicesType
{
    /**
     * @param array $ids
     * @return Dto
     * @access static
     */
    public static function loadFromFaker( array $ids = [] ) : Dto
    {
        $faker = Faker\Factory::create();

        $dto = Dto::factoryPrimaryAccountId(
            (string) $faker->numberBetween( 1000, 9999 )
        )
                  ->setName( $faker->word );

        $max  = $faker->numberBetween( 2, 4 );
        $load = [];
        for( $x = 0; $x < $max; $x++ ) {
            $load[] = (string)  $faker->numberBetween( 1000, 9999 );
        }
        $dto->setSecondaryAccountRef( $load );

        $max = $faker->numberBetween( 2, 4 );
        $load = [];
        for( $x = 0; $x < $max; $x++ ) {
            $id = empty( $ids ) ? $faker->numberBetween( 60000, 69999 ) : array_rand( array_flip( $ids ));
            $load[] = SupplierInvoiceType::loadFromFaker( $id );
        }
        $dto->setSupplierInvoice( $load );

        return $dto;
    }
}
