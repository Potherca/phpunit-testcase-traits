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
 * @return string[]
 */
function createShimForTrait($testCase, $methodName, $traitName)
{
    static $class;

    if (isset($class[$traitName]) === false) {

        $delimiter = '\\';

        $name = substr($traitName, 0, -5); // 5 = "Trait"
        $parts = explode($delimiter, $name);

        $shimClass = array_pop($parts);
        $parts[] = 'Shim';
        $parts[] = $shimClass;

        $shimClass = implode($delimiter, $parts);

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

        $class[$traitName] = new $shimClass($testCase);
    }

    return array($class[$traitName], $methodName);
}

/*EOF*/
