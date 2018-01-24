<?php

namespace Potherca\PhpUnit;

use PHPUnit\Framework\TestCase;

abstract class AbstractTestCase extends TestCase
{
    //////////////////////////////// PUBLIC API \\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\

    /**
     * @param int $argumentCount
     *
     * @return array
     */
    final public function createMissingArgumentExpectation($argumentCount)
    {
        $expectedExceptions = array(
            '5' => class_exists('\\PHPUnit_Framework_Error') ? '\\PHPUnit_Framework_Error' : '\\PHPUnit\\Framework\\Error',
            '7' => '\\ArgumentCountError',
            '70' => class_exists('\\PHPUnit_Framework_Error_Warning') ? '\\PHPUnit_Framework_Error_Warning' : '\\PHPUnit\\Framework\\Error\\Warning',
        );

        $expectedExceptionMessages = array(
            '5' => 'Missing argument ' . $argumentCount,
            '7' => 'Too few arguments',
            '70' => 'Missing argument ' . $argumentCount,
        );

        $function = $this->getExpectExpectationMethodName();

        $expectedException = $expectedExceptions[PHP_MAJOR_VERSION];
        if (array_key_exists(PHP_MAJOR_VERSION . PHP_MINOR_VERSION, $expectedExceptions) === true) {
            $expectedException = $expectedExceptions[PHP_MAJOR_VERSION . PHP_MINOR_VERSION];
        }

        $expectedExceptionMessage = $expectedExceptionMessages[PHP_MAJOR_VERSION];
        if (array_key_exists(PHP_MAJOR_VERSION . PHP_MINOR_VERSION, $expectedExceptionMessages) === true) {
            $expectedExceptionMessage = $expectedExceptionMessages[PHP_MAJOR_VERSION . PHP_MINOR_VERSION];
        }

        return array($function, $expectedException, $expectedExceptionMessage);
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

    ////////////////////////////// UTILITY METHODS \\\\\\\\\\\\\\\\\\\\\\\\\\\\\
}

/*EOF*/
