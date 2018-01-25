<?php

namespace Potherca\PHPUnit\Example\GetCompatibleExceptionName;

class Example
{
    public function __construct(array $value) {}
}

abstract class AbstractTestCase extends \PHPUnit\Framework\TestCase {}

class ExampleTest extends AbstractTestCase
{
    use \Potherca\PhpUnit\Traits\GetCompatibleExceptionNameTrait;

    public function testArithmeticError()
    {
        // Please note that `\ArithmeticError::class` is NOT used, as this will cause an error if `ArithmeticError` does not exist.
        $exceptionName = $this->getCompatibleExceptionName('\\ArithmeticError');

        if (method_exists($this, 'expectExceptionMessage')) {
            /* PHPUnit ^5.2 | ^6.0 */
            $this->expectException($exceptionName);
            $this->expectExceptionMessage('Bit shift by negative number');
        } else {
            /* PHPUnit ^4.3 | =< 5.6 */
            $this->setExpectedExceptionRegExp($exceptionName, 'Bit shift by negative number');
        }

        /** @noinspection PhpExpressionResultUnusedInspection */
        1 >> -1;
    }

    public function testArgumentCountError()
    {
        // Please note that `\ArgumentCountError::class` is NOT used, as this will cause an error if `ArgumentCountError` does not exist.
        $exceptionName = $this->getCompatibleExceptionName('\\ArgumentCountError');

        if (method_exists($this, 'expectExceptionMessageRegExp')) {
            /* PHPUnit ^5.2 | ^6.0 */
            $this->expectException($exceptionName);
            $this->expectExceptionMessageRegExp('/none given|0 passed/');
        } else {
            /* PHPUnit ^4.3 | =< 5.6 */
            $this->setExpectedExceptionRegExp($exceptionName, '/none given|0 passed/');
        }

        /** @noinspection PhpParamsInspection */
        new Example();
    }

    public function testDivisionByZeroError()
    {
        // Please note that `\DivisionByZeroError::class` is NOT used, as this will cause an error if `DivisionByZeroError` does not exist.
        $exceptionName = $this->getCompatibleExceptionName('\\DivisionByZeroError');

        if (method_exists($this, 'expectExceptionMessage')) {
            /* PHPUnit ^5.2 | ^6.0 */
            $this->expectException($exceptionName);
            $this->expectExceptionMessage('Division by zero');
        } else {
            /* PHPUnit ^4.3 | =< 5.6 */
            $this->setExpectedException($exceptionName, 'Division by zero');
        }

        /** @noinspection PhpExpressionResultUnusedInspection */
        0 / 0;
    }

    public function testTypeError()
    {
        // Please note that `\TypeError::class` is NOT used, as this will cause an error if `TypeError` does not exist.
        $exceptionName = $this->getCompatibleExceptionName('\\TypeError');

        if (method_exists($this, 'expectExceptionMessageRegExp')) {
            /* PHPUnit ^5.2 | ^6.0 */
            $this->expectException($exceptionName);
            $this->expectExceptionMessageRegExp('/must be of the type array|must be an array/');
        } else {
            /* PHPUnit ^4.3 | =< 5.6 */
            $this->setExpectedExceptionRegExp($exceptionName, '/must be of the type array|must be an array/');
        }

        /** @noinspection PhpParamsInspection */
        new Example(false);
    }
}
