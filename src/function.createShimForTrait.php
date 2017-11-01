<?php

namespace Potherca\PhpUnit;

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

        $class[$traitName] = new $shimClass($testCase);
    }

    return array($class[$traitName], $methodName);
}

/*EOF*/
