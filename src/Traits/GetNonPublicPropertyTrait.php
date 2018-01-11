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
 * Retrieve the value of a non-public property of an object.
 *
 * One some occasions it is desirable to read the value of an object's private
 * or protected property. For instance when an object does not have a getter for
 * a given property.
 *
 * This trait offers a method to read the value of an object's property.
 *
 * Example usage:
 *
 *    class Example
 *    {
 *        private $name;
 *
 *        public function __construct($name)
 *        {
 *            $this->name = $name;
 *        }
 *    }
 *
 *    class ExampleTest extends \PHPUnit\Framework\TestCase
 *    {
 *        use \Potherca\PhpUnit\Traits\GetNonPublicPropertyTrait;
 *
 *        const MOCK_VALUE = 'mock-value';
 *
 *        public function testReadHiddenProperty()
 *        {
 *            $example = new Example(self::MOCK_VALUE);
 *
 *            $expected = self::MOCK_VALUE;
 *            $actual = $this->getNonPublicProperty($example, 'name';
 *
 *            $this->assertEquals($expected, $actual);
 *        }
 *    }
 */
trait GetNonPublicPropertyTrait
{
    //////////////////////////////// PUBLIC API \\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\

    /**
     * Gets the value for a given (private or protected) property on a given object
     *
     * @param object $subject
     * @param string $name
     *
     * @return mixed
     */
    final public function getNonPublicProperty($subject, $name)
    {
        return call_user_func_array(
            \Potherca\PhpUnit\Shim\Util::createShimForTrait($this,  __FUNCTION__, __TRAIT__),
            func_get_args()
        );
    }
}

/*EOF*/
