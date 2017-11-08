<?php

namespace Potherca\PhpUnit\Shim;

/**
 * @TODO: Add support for `ArithmeticError`
 *
 * @method expectExceptionMessage($message)
 * @method fail($message)
 * @method markTestSkipped($message)
 */
class GetCompatibleExceptionName extends AbstractTraitShim
{
    //////////////////////////////// PUBLIC API \\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\

    /**
     * @param string $exceptionName
     *
     * @return string
     *
     * @throws \InvalidArgumentException
     * @throws \PHPUnit_Framework_Exception | \PHPUnit\Framework\Exception
     * @throws \PHPUnit_Framework_AssertionFailedError|\PHPUnit\Framework\AssertionFailedError
     * @throws \PHPUnit_Framework_SkippedTestError|\PHPUnit\Framework\SkippedTestError
     */
    final public function getCompatibleExceptionName($exceptionName)
    {
        $matchingExceptionName = '';

        try {
            $exceptionName = $this->getExistingClassName($exceptionName);
        } catch (\InvalidArgumentException $e) {
            // Not an existing class, needs conversion
        }

        $exceptionName = ltrim($exceptionName, '\\');

        if ($this->isPhpUnitExceptionNeeded($exceptionName) === false) {
            if ($exceptionName === 'DivisionByZeroError') {
                $this->getTestcase()->expectExceptionMessage('Division by zero');
                $matchingExceptionName = '\PHPUnit_Framework_Error_Warning';
            } else {
                $matchingExceptionName = '\\'.$exceptionName;
            }
        } else {
            if ($exceptionName === 'ParseError') {
                $this->getTestcase()->markTestSkipped('Parse errors can not be caught in PHP5');
            } else {
                $exceptionName = $this->getMatchingExceptionName($exceptionName);

                $alternative = '';
                if ($exceptionName === '\PHPUnit_Framework_Error') {
                    $alternative = 'PHPUnit\Framework\Error\Error';
                }

                $matchingExceptionName = $this->getExistingClassName($exceptionName, $alternative);
            }
        }

        return $matchingExceptionName;
    }

    ////////////////////////////// UTILITY METHODS \\\\\\\\\\\\\\\\\\\\\\\\\\\\\

    /**
     * @param string $exceptionName
     *
     * @return bool
     */
    private function isPhpUnitExceptionNeeded($exceptionName)
    {
        return class_exists('\\' . $exceptionName) === false
            /* @NOTE: The line below validates that the Exception does not extend the PHP7 "Throwable" interface */
            || class_implements('\\' . $exceptionName) === array();
    }

    /**
     * @param $exceptionName
     *
     * @return string
     *
     * @throws \PHPUnit\Framework_AssertionFailedError | \PHPUnit\Framework\AssertionFailedError
     */
    private function getMatchingExceptionName($exceptionName)
    {
        $matchingExceptions = array(
            'ArgumentCountError' => '\PHPUnit_Framework_Error',
            'AssertionError' => '\PHPUnit_Framework_Error_Warning',
            'DivisionByZeroError' => '\PHPUnit_Framework_Error_Warning',
            'Error' => '\PHPUnit_Framework_Error',
            'TypeError' => '\PHPUnit_Framework_Error',
        );

        if (array_key_exists($exceptionName, $matchingExceptions) === false) {
            $errorMessage = vsprintf('Could not find an exception for class name "%s"', array($exceptionName));
            $this->getTestcase()->fail($errorMessage);
        }

        return $matchingExceptions[$exceptionName];
    }
}

/*EOF*/
