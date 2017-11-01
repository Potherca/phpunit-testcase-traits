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
namespace Potherca\PhpUnit;

/**
 * Provide names of PHP5 compatible PHPUnit_Framework_Exception for given (new) PHP7 Exceptions.
 *
 * Tests code that runs on both PHP version 5 and 7 can will run into trouble
 * when using PHPUnit's `expectException` function in combination with Errors
 * and Warnings that extend the new PHP7 `Error` class.
 *
 * Most of these errors and warnings can be avoided by more robust code but
 * there are cases where this is not the case.
 *
 * One such example is when a type-hint is not met. In PHP5 this will trigger
 * an error, in PHP7 this will throw an TypeError exception.
 *
 * This trait provides a solution by returning the PHP5 PHPUnit_Framework_Exception
 * that matches the PHP7 Error.
 *
 * Example usage:
 *
 *    class Example
 *    {
 *        public function __constructor(array $value) {}
 *    }
 *
 *    class ExampleTest extends PHPUnit\Framework\TestCase
 *    {
 *        use \Potherca\PhpUnit\GetCompatibleExceptionNameTrait;
 *
 *        public function testException()
 *        {
 *            // Please not that `\TypeError::class` is NOT used, as this will cause an error if `TypeError` does not exist.
 *            $exceptionName = $this->getCompatibleExceptionName('\TypeError');
 *
 *            $this->expectException($exceptionName);
 *            $this->expectExceptionMessageRegExp('/none given|0 passed/');
 *
 *            $example = new Example();
 *        }
 *    }
 *
 * @TODO: Add support for `ArithmeticError`
 *
 * @method expectExceptionMessage($message)
 * @method fail($message)
 * @method markTestSkipped($message)
 */
trait GetCompatibleExceptionNameTrait
{
    //////////////////////////////// PUBLIC API \\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\

    /**
     * @param string $exceptionName
     *
     * @return string
     *
     * @throws \PHPUnit_Framework_AssertionFailedError|\PHPUnit\Framework\AssertionFailedError
     * @throws \PHPUnit_Framework_SkippedTestError|\PHPUnit\Framework\SkippedTestError
     */
    final public function getCompatibleExceptionName($exceptionName)
    {
        return call_user_func_array(
            \Potherca\PhpUnit\createShimForTrait($this,  __FUNCTION__, __TRAIT__),
            func_get_args()
        );
    }
}

/*EOF*/
