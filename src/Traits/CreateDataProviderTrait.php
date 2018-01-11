<?php
/**
 * Copyright (C) 2017  Potherca
 *
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program.  If not, see <http://www.gnu.org/licenses/>.
 */
namespace Potherca\PhpUnit\Traits;

/**
 * Creates a "key => [value]" pair from a given one-dimensional array of values,
 * which is meant to be returned from a data-provider method in a TestCase.
 *
 * The keys are created from the array values. The returned array is sorted by
 * key name. Key names that are longer than 25 characters are truncated.
 *
 * The trait provides two setter methods to change this behaviour:
 *
 * - CreateDataProviderTrait::setDataProviderMaximumKeyLength(int $maxLength)
 * - CreateDataProviderTrait::setDataProviderSortByKey(bool $sort)
 *
 * Example usage:
 *
 *    class ExampleTest extends PHPUnit\Framework\TestCase
 *    {
 *        use \Potherca\PhpUnit\Traits\CreateDataProviderTrait;
 *
 *        public function provideValidValues()
 *        {
 *            return $this->createDataProvider([
 *                -1,
 *                0,
 *                1,
 *                true,
 *                false,
 *                null,
 *                [null],
 *                [],
 *                new \stdClass(),
 *            ]);
 *        }
 *    }
 *
 *    var_export((new ExampleTest)->provideValidValues());
 *
 * Output would be:
 *
 *    array (
 *        'NULL' => [NULL],
 *        'array: 1 (count: 1)' => [
 *            [NULL]
 *        ],
 *        'array: 2 (count: 0)' => [
 *            []
 *        ],
 *        'boolean: false' => [false],
 *        'boolean: true' => [true],
 *        'integer: -1' => [-1],
 *        'integer: 0' => [0],
 *        'integer: 1' => [1],
 *        'object: stdClass' => [new stdClass(array())],
 *    );
 *
 * @method fail($message)
 */
trait CreateDataProviderTrait
{
    //////////////////////////// SETTERS AND GETTERS \\\\\\\\\\\\\\\\\\\\\\\\\\\

    /** @param int $length */
    final public function setDataProviderMaximumKeyLength($length)
    {
        call_user_func_array(
            \Potherca\PhpUnit\Shim\Util::createShimForTrait($this,  __FUNCTION__, __TRAIT__),
            func_get_args()
        );
    }

    /** @param bool $sort */
    final public function setDataProviderSortByKey($sort)
    {
        call_user_func_array(
            \Potherca\PhpUnit\Shim\Util::createShimForTrait($this,  __FUNCTION__, __TRAIT__),
            func_get_args()
        );
    }

    //////////////////////////////// PUBLIC API \\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\

    /**
     * @param array $subject
     *
     * @return array[]
     */
    final public function createDataProvider(array $subject)
    {
        return call_user_func_array(
            \Potherca\PhpUnit\Shim\Util::createShimForTrait($this,  __FUNCTION__, __TRAIT__),
            func_get_args()
        );
    }
}

/*EOF*/
