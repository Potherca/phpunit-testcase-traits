<?php

namespace Potherca\PhpUnit\Shim;

/**
 * Traits were not introduced until PHP5.4 so for older versions (i.e. PHP5.3)
 * this function loads the trait's functionality. This function should be called
 * from a magic `__call` method:
 *
 *      class ExampleTest extends \PHPUnit_Framework_TestCase
 *      {
 *          // ....
 *
 *          final public function __call($name, array $parameters)
 *          {
 *              require_once '/path/to/this/file.php';
 *              return \Potherca\PhpUnit\Shim\traitShim($this, $name, $parameters);
 *          }
 *          // ....
 *      }
 *
 * Alternatively, instead of using `require_once`, this file could be loaded
 * using Composer's autoloader.
 *
 * @param \PHPUnit_Framework_TestCase | \PHPUnit\Framework\TestCase $testcase
 * @param string $functionName
 * @param array $parameters
 *
 * @return mixed
 */
function traitShim($testcase, $functionName, array $parameters)
{
    $result = null;

    if ($testcase instanceof \PHPUnit\Framework\TestCase === false &&
        $testcase instanceof \PHPUnit_Framework_TestCase === false
    ) {
        $type = gettype($testcase);

        if ($type === 'object') {
            $type = get_class($testcase);
        }

        $message = vsprintf(
            'Argument 1 passed to %s must be an instance of %s, %s given',
            array(
                __METHOD__,
                '"\PHPUnit_Framework_TestCase" or "\PHPUnit\Framework\TestCase"',
                $type,
            )
        );
        throw new \InvalidArgumentException($message);
    }

    $traitShimClass =  __NAMESPACE__.'\\'.ucfirst($functionName);

    if (class_exists($traitShimClass)) {
        $object = new $traitShimClass($testcase);
        $result = $object($parameters);
    }

    return $result;
}
