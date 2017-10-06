<?php

namespace Potherca\PhpUnit\Traits;

use Potherca\PhpUnit\Shim\AbstractTraitShim;

trait CreateClassForTraitTrait
{
    /**
     * @param string $traitName
     *
     * @return AbstractTraitShim
     */
    public function createClassForTrait($traitName)
    {
        $delimiter = '\\';

        $name = substr($traitName, 0, -5); // 5 = "Trait"
        $parts = explode($delimiter, $name);

        $className = array_pop($parts);
        $parts[] = 'Shim';
        $parts[] = $className;

        $className = implode($delimiter, $parts);

        if (class_exists($className) === false) {
            $message = vsprintf('Could not find class "%s" to create for trait "%s"', [$className, $traitName]);
            throw new \InvalidArgumentException($message);
        }

        return new $className($this);
    }
}

/*EOF*/
