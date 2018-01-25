<?php

namespace Potherca\PhpUnit\Shim;

use Potherca\PhpUnit\InvalidArgumentException;

/**
 * @method expectExceptionMessage($message)
 * @method fail($message)
 * @method markTestSkipped($message)
 */
class GetCompatibleExceptionName extends AbstractTraitShim
{
    //////////////////////////////// PUBLIC API \\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\
    const ARGUMENT_COUNT_ERROR_WITH_TYPE_HINT = 'type-hint';
    const ARGUMENT_COUNT_ERROR_WITHOUT_TYPE_HINT = 'no-type-hint';
    const ERROR_CONTEXT_NEEDED = '%s needs a context to decide which exception name to provide. Available options are: "%s"';

    /**
     * @param string $exceptionName
     * @param string $context
     *
     * @return string
     *
     * @throws InvalidArgumentException
     *
     * @throws \PHPUnit_Framework_Exception|\PHPUnit\Framework\Exception
     * @throws \PHPUnit_Framework_AssertionFailedError|\PHPUnit\Framework\AssertionFailedError
     * @throws \PHPUnit_Framework_SkippedTestError|\PHPUnit\Framework\SkippedTestError
     */
    final public function getCompatibleExceptionName($exceptionName, $context = '')
    {
        $matchingExceptionName = '';
        $alternative = '';

        try {
            $exceptionName = $this->getExistingClassName($exceptionName);
        } catch (InvalidArgumentException $e) {
            // Not an existing class, needs conversion
        }

        $exceptionName = ltrim($exceptionName, '\\');

        if ($this->isPhpUnitExceptionNeeded($exceptionName) === false) {
            if ($exceptionName === 'DivisionByZeroError') {
                $this->getTestcase()->expectExceptionMessage('Division by zero');
                $matchingExceptionName = '\\PHPUnit_Framework_Error_Warning';
            } else {
                $matchingExceptionName = '\\'.$exceptionName;
            }
        } else {
            if ($exceptionName === 'ParseError') {
                $this->getTestcase()->markTestSkipped('Parse errors can not be caught in PHP5');
            } elseif ($exceptionName === 'ArithmeticError') {
                /* PHP 7.0 thrown when an error occurs while performing mathematical operations.
                 * As I have not been able to find the PHP5 equivalent, marking as skipped until
                 * a working example is available.
                 */
                $this->getTestcase()->markTestSkipped('There are no equivalent for Arithmetic errors in PHP5 ');
            } elseif ($exceptionName === 'ArgumentCountError') {
                /* PHP 7.1 thrown when too few arguments are passed to a user-defined function or method.
                 * PHP 7.0 throws a TypeError if a type-hint is present or a warning if no
                 * type-hint is present. The only way to know which one is needed is to ask the
                 * calling side.
                 */
                $candidates = array(
                    self::ARGUMENT_COUNT_ERROR_WITH_TYPE_HINT => $this->getCompatibleExceptionName('\\TypeError'),
                    self::ARGUMENT_COUNT_ERROR_WITHOUT_TYPE_HINT => '\\PHPUnit_Framework_Error',
                );

                $candidate = self::ARGUMENT_COUNT_ERROR_WITHOUT_TYPE_HINT;

                if (PHP_MAJOR_VERSION.PHP_MINOR_VERSION === '70') {
                    if (array_key_exists((string) $context, $candidates) === false) {
                        $exception = $this->getExistingClassName('\\PHPUnit\\Framework\\Exception');
                        $error = vsprintf(self::ERROR_CONTEXT_NEEDED, array(
                            'function' => __FUNCTION__,
                            'candidates' => implode('", "', array_keys($candidates))
                        ));
                        throw new $exception($error);
                    }
                    $candidate = $context;
                }
                $matchingExceptionName = $candidates[$candidate];
            } else {
                $matchingExceptionName = $this->getMatchingExceptionName($exceptionName);

            }
        }

        if ($matchingExceptionName === '\\PHPUnit_Framework_Error') {
            $alternative = '\\PHPUnit_Framework_Error_Error';
        }

        return $this->getExistingClassName($matchingExceptionName, $alternative);
    }

    ////////////////////////////// UTILITY METHODS \\\\\\\\\\\\\\\\\\\\\\\\\\\\\

    /**
     * @param string $exceptionName
     *
     * @return bool
     */
    private function isPhpUnitExceptionNeeded($exceptionName)
    {
        $exists = class_exists('\\' . $exceptionName);

        $extends = false;
        if ($exists === true) {
            /* @NOTE: This validates the Exception does not extend the PHP7 "Throwable" interface */
            $extends = class_implements('\\' . $exceptionName) !== array();
        }

        return $exists === false && $extends === false;
    }

    /**
     * @param $exceptionName
     *
     * @return string
     *
     * @throws \PHPUnit_Framework_AssertionFailedError | \PHPUnit\Framework\AssertionFailedError
     */
    private function getMatchingExceptionName($exceptionName)
    {
        $matchingExceptions = array(
            // PHP 7.0 thrown when an assertion made via assert() fails.
            'AssertionError' => '\\PHPUnit_Framework_Error_Warning',
            // PHP 7.0 thrown when an attempt is made to divide a number by zero.
            'DivisionByZeroError' => '\\PHPUnit_Framework_Error_Warning',
            // PHP 7.0 base class for all internal PHP errors.
            'Error' => '\\PHPUnit_Framework_Error',
            // PHP 7.0 thrown in one of three circumstances:
            // - an argument type passed to a function does not match the declared parameter type.
            // - a value returned from a function does not match the declared return type.
            // - an invalid number of arguments are passed to a built-in PHP function (strict mode only).
            'TypeError' => '\\PHPUnit_Framework_Error',
        );

        if (array_key_exists($exceptionName, $matchingExceptions) === false) {
            $errorMessage = vsprintf('Could not find an exception for class name "%s"', array($exceptionName));
            $this->getTestcase()->fail($errorMessage);
        }

        return $matchingExceptions[$exceptionName];
    }
}

/*EOF*/
