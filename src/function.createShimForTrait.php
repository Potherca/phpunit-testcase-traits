<?php

namespace Potherca\PhpUnit;

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
 * `call_user_func_array`.
 *
 * @param \PHPUnit_Framework_TestCase| \PHPUnit\Framework\TestCase $testCase
 * @param string $methodName
 * @param string $traitName
 *
 * @throws \InvalidArgumentException If no Shim class can be found for a given trait
 *
 * @return Callable
 */
function createShimForTrait($testCase, $methodName, $traitName)
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

            $start = strlen(__NAMESPACE__)+1;
            $end = -5;  // 5 = "Trait"
            $name = substr($traitName, $start, $end);

            $shimClass = vsprintf('%s\\Shim\\%s', array(__NAMESPACE__, $name));

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

/*EOF*/
