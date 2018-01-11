<?php

namespace Potherca\PhpUnit\Shim;

class Util
{
    const NAMESPACE_PREFIX_LENGTH = 17; // = strlen('Potherca\\PhpUnit\\');
    const NAMESPACE_SUFFIX_LENGTH = -5; // = strlen('Trait');

    /**
     * Traits were not introduced until PHP5.4 so for older versions (i.e. PHP5.3)
     * this method loads the trait's functionality. This function should be called
     * from a magic `__call` method:
     *
     *      class ExampleTest extends \PHPUnit_Framework_TestCase
     *      {
     *          // ....
     *
     *          final public function __call($name, array $parameters)
     *          {
     *              return \Potherca\PhpUnit\Shim\Util::traitShim($this, $name, $parameters);
     *          }
     *          // ....
     *      }
     *
     * @param \PHPUnit_Framework_TestCase | \PHPUnit\Framework\TestCase $testcase
     * @param string $functionName
     * @param array $parameters
     *
     * @return mixed
     *
     * @throws \InvalidArgumentException
     */
    final public static function traitShim($testcase, $functionName, array $parameters)
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

    /**
     * Returns an array containing a Shim class- and method-name for given trait.
     *
     * Given the name of a trait (for example `Potherca\PhpUnit\FooTrait`) and
     * method this function will check a Shim class for the given trait exists (for
     * the given example `Potherca\PhpUnit\Shim\Foo`) and return an array
     * referencing it.
     *
     * A TestCase must be given as the TraitShimInterface requires it for the class'
     * construction.
     *
     * The returned array is meant to be used as callable for `call_user_func` or
     * `call_user_func_array`:
     *
     *      trait ExampleTrait
     *      {
     *          // ....
     *
     *          final public function exampleMethod($param, array $parameters)
     *          {
     *              call_user_func_array(
     *                  \Potherca\PhpUnit\Shim\Util::createShimForTrait($this,  __FUNCTION__, __TRAIT__),
     *                  func_get_args()
     *              );
     *          }
     *
     *          // ....
     *      }
     *
     * @param \PHPUnit_Framework_TestCase| \PHPUnit\Framework\TestCase $testCase
     * @param string $methodName
     * @param string $traitName
     *
     * @throws \InvalidArgumentException If no Shim class can be found for a given trait
     *
     * @return Callable
     */
    final public static function createShimForTrait($testCase, $methodName, $traitName)
    {
        static $class;

        $key = $traitName.'::'.$methodName;

        if (isset($class[$key]) === false) {

            $subject = $testCase;

            $exists = false;
            do {

                if (
                    ($subject === 'PHPUnit\\Framework\\TestCase' || $subject === 'PHPUnit_Framework_TestCase')
                    && method_exists($subject, $methodName) === true
                ) {
                    $exists = $subject;
                }
            } while ($subject = get_parent_class($subject));

            if ($exists !== false) {
                $class[$key] = $exists;
            } else {
                // Only create shim class if native function does not exist

                $name = substr($traitName, self::NAMESPACE_PREFIX_LENGTH, self::NAMESPACE_SUFFIX_LENGTH);

                $shimClass = vsprintf('%s\\%s', array(__NAMESPACE__, $name));

                if (method_exists($shimClass, $methodName) === false) {
                    /* CHECKME: Shouldn't we always check METHOD instead of TRAIT as class and method are equals for shims ? */
                    /* Shim class does not match trait name, fallback to Shim class based on method name */
                    $end = strrpos($shimClass, '\\');
                    $name = substr($shimClass, 0, $end);

                    $shimClass = vsprintf('%s\\%s', array($name, ucfirst($methodName)));
                }

                if (class_exists($shimClass) === false) {
                    $message = vsprintf('Could not find class "%s" to create for trait "%s"', array($shimClass, $traitName));
                    throw new \InvalidArgumentException($message);
                }

                $implements = class_implements($shimClass);
                $interface = 'Potherca\PhpUnit\Shim\TraitShimInterface';

                if (in_array($interface, $implements, true) === false) {
                    $message = vsprintf(
                        'Found class "%s" for trait "%s" but it does not implement "%s"',
                        array($shimClass, $traitName, $interface)
                    );
                    throw new \InvalidArgumentException($message);
                }

                $class[$key] = new $shimClass($testCase);
            }
        }

        return array($class[$key], $methodName);
    }

}

/*EOF*/
