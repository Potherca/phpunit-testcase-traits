<?php

namespace Potherca\PhpUnit;

use PHPUnit\Framework\TestCase;

abstract class AbstractTestCase extends TestCase
{
    //////////////////////////// SETTERS AND GETTERS \\\\\\\\\\\\\\\\\\\\\\\\\\\

    /** @return string[] */
    private function getArgumentCountError()
    {
        $expectedExceptions = array(
            '5' => class_exists('\\PHPUnit_Framework_Error') ? '\\PHPUnit_Framework_Error' : '\\PHPUnit\\Framework\\Error',
            '7' => '\\ArgumentCountError',
            '70' => class_exists('\\PHPUnit_Framework_Error_Warning') ? '\\PHPUnit_Framework_Error_Warning' : '\\PHPUnit\\Framework\\Error\\Warning',
        );
        $expectedException = $expectedExceptions[PHP_MAJOR_VERSION];
        if (array_key_exists(PHP_MAJOR_VERSION . PHP_MINOR_VERSION, $expectedExceptions) === true) {
            $expectedException = $expectedExceptions[PHP_MAJOR_VERSION . PHP_MINOR_VERSION];
        }

        return $expectedException;
    }

    /** @return string[] */
    private function getArgumentTypeError()
    {
        $expectedExceptions = array(
            '5' => class_exists('\\PHPUnit_Framework_Error') ? '\\PHPUnit_Framework_Error' : '\\PHPUnit\\Framework\\Error',
            '7' => '\\TypeError',
        );
        $expectedException = $expectedExceptions[PHP_MAJOR_VERSION];
        if (array_key_exists(PHP_MAJOR_VERSION . PHP_MINOR_VERSION, $expectedExceptions) === true) {
            $expectedException = $expectedExceptions[PHP_MAJOR_VERSION . PHP_MINOR_VERSION];
        }

        return $expectedException;
    }

    /**
     * @return string
     */
    final public function getExpectExpectationMethodName()
    {
        $function = 'setExpectedException';

        if (method_exists($this, $function) === false) {
            $function = 'expectException';
        }

        return $function;
    }

    /**
     * @return string
     */
    final public function getExpectExpectationRegexpMethodName()
    {
        $function = 'setExpectedExceptionRegExp';
        if (method_exists($this, $function) === false) {
            $function = 'expectExceptionMessageRegExp';
        }

        return $function;
    }

    //////////////////////////////// PUBLIC API \\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\

    /**
     * @param int $argumentCount
     *
     * @return string[]
     */
    final public function createMissingArgumentExpectation($argumentCount)
    {
        $function = $this->getExpectExpectationMethodName();

        $expectedException = $this->getArgumentCountError();

        $expectedExceptionMessage = $this->getMissingArgumentMessage($argumentCount);

        return array($function, $expectedException, $expectedExceptionMessage);
    }

    /**
     * @param string $argumentType
     *
     * @return string[]
     */
    final public function createWrongArgumentTypeExpectation($argumentType)
    {
        $function = $this->getExpectExpectationMethodName();

        $expectedException = $this->getArgumentTypeError();

        $expectedExceptionMessage = $this->getWrongArgumentTypeMessage($argumentType);

        return array($function, $expectedException, $expectedExceptionMessage);
    }

    ////////////////////////////// UTILITY METHODS \\\\\\\\\\\\\\\\\\\\\\\\\\\\\

    /**
     * @param int $argumentCount
     *
     * @return string[]
     */
    private function getMissingArgumentMessage($argumentCount)
    {
        $expectedExceptionMessages = array(
            '5' => 'Missing argument ' . $argumentCount,
            '7' => 'Too few arguments',
            '70' => 'Missing argument ' . $argumentCount,
        );
        $expectedExceptionMessage = $expectedExceptionMessages[PHP_MAJOR_VERSION];
        if (array_key_exists(PHP_MAJOR_VERSION . PHP_MINOR_VERSION, $expectedExceptionMessages) === true) {
            $expectedExceptionMessage = $expectedExceptionMessages[PHP_MAJOR_VERSION . PHP_MINOR_VERSION];
        }

        return $expectedExceptionMessage;
    }

    /**
     * @param string $argumentType
     *
     * @return string[]
     */
    private function getWrongArgumentTypeMessage($argumentType)
    {
        $expectedExceptionMessages = array(
            '5' => 'must be of the type ' . $argumentType,
            '7' => 'must be of the type ' . $argumentType,
            '53' => 'must be an ' . $argumentType,
            '71' => 'Too few arguments',
        );
        $expectedExceptionMessage = $expectedExceptionMessages[PHP_MAJOR_VERSION];
        if (array_key_exists(PHP_MAJOR_VERSION . PHP_MINOR_VERSION, $expectedExceptionMessages) === true) {
            $expectedExceptionMessage = $expectedExceptionMessages[PHP_MAJOR_VERSION . PHP_MINOR_VERSION];
        }

        return $expectedExceptionMessage;
    }
}

/*EOF*/
