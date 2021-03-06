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
 * Change the value of a non-public property of an object.
 *
 * One some occasions it is desirable to change an object's private or protected
 * property. For instance when an object is usually created by a factory which
 * also has access to those hidden properties.
 *
 * This trait offers a method to change the value of an object's property.
 *
 * Example usage:
 *
 *    class Example
 *    {
 *        private $name;
 *
 *        public function getName()
 *        {
 *            return $this->name;
 *        }
 *    }
 *
 *    class ExampleTest extends \PHPUnit\Framework\TestCase
 *    {
 *        use \Potherca\PhpUnit\Traits\SetNonPublicPropertyTrait;
 *
 *        const MOCK_VALUE = 'mock-value';
 *
 *        public function testHiddenProperty()
 *        {
 *            $example = new Example();
 *
 *            $this->setNonPublicProperty($example, 'name', self::MOCK_VALUE);
 *
 *            $expected = self::MOCK_VALUE;
 *            $actual = $example->getName();
 *
 *            $this->assertEquals($expected, $actual);
 *        }
 *    }
 */
trait SetNonPublicPropertyTrait
{
    //////////////////////////////// PUBLIC API \\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\

    /**
     * Sets a given value for a given (private or protected) property on a given object
     *
     * @param object $subject
     * @param string $name
     * @param mixed $value
     */
    final public function setNonPublicProperty($subject, $name, $value)
    {
        call_user_func_array(
            \Potherca\PhpUnit\Shim\Util::createShimForTrait($this,  __FUNCTION__, __TRAIT__),
            func_get_args()
        );
    }
}

/*EOF*/
