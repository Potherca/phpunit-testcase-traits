<?php

namespace Potherca\PHPUnit\Example\GetCompatibleExceptionName;

use Potherca\PhpUnit\Shim\GetCompatibleExceptionName;

class Example
{
    public function __construct(array $value) {}
    public function example($value) {}
}

abstract class AbstractTestCase extends \PHPUnit\Framework\TestCase {}

class ExampleTest extends AbstractTestCase
{
    /**
     * As PHP5.3 does not support traits, __call is (a)bused instead of the trait.
     *
    use \Potherca\PhpUnit\Traits\GetCompatibleExceptionNameTrait;
     *
     * @param string $name
     * @param array $parameters
     *
     * @return mixed
     */
    final public function __call($name, array $parameters)
    {
        return \Potherca\PhpUnit\Shim\Util::traitShim($this, $name, $parameters);
    }

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

    public function testArgumentCountErrorWithTypeHint()
    {
        // Please note that `\ArgumentCountError::class` is NOT used, as this will cause an error if `ArgumentCountError` does not exist.
        $exceptionName = $this->getCompatibleExceptionName('\\ArgumentCountError', GetCompatibleExceptionName::ARGUMENT_COUNT_ERROR_WITH_TYPE_HINT);

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

    public function testArgumentCountErrorWithoutTypeHint()
    {
        // Please note that `\ArgumentCountError::class` is NOT used, as this will cause an error if `ArgumentCountError` does not exist.
        $exceptionName = $this->getCompatibleExceptionName('\\ArgumentCountError', GetCompatibleExceptionName::ARGUMENT_COUNT_ERROR_WITHOUT_TYPE_HINT);

        if (method_exists($this, 'expectExceptionMessageRegExp')) {
            /* PHPUnit ^5.2 | ^6.0 */
            $this->expectException($exceptionName);
            $this->expectExceptionMessageRegExp('/Too few arguments to function|Missing argument 1/');
        } else {
            /* PHPUnit ^4.3 | =< 5.6 */
            $this->setExpectedExceptionRegExp($exceptionName, '/Too few arguments to function|Missing argument 1/');
        }

        $example = new Example(array());

        /** @noinspection PhpParamsInspection */
        $example->example();
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
