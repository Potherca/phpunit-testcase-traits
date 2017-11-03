<?php

namespace Potherca\PhpUnit\Shim;

class SetNonPublicProperty extends GetNonPublicProperty
{
    //////////////////////////////// PUBLIC API \\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\

    /**
     * Sets a given value for a given (private or protected) property on a given object
     *
     * @param object $subject
     * @param string $name
     * @param mixed $value
     *
     * @return mixed
     *
     * @throws \InvalidArgumentException
     */
    final public function setNonPublicProperty($subject, $name, $value)
    {
        $originalValue = $this->getNonPublicProperty($subject, $name);

        $reflectionProperty = $this->getPropertyFromObject($subject, $name);
        $reflectionProperty->setValue($subject, $value);

        return $originalValue;
    }
}

/*EOF*/
