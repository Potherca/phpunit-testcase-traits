<?php

namespace Potherca\PhpUnit\Shim;

class SetNonPublicProperty extends AbstractTraitShim
{
    //////////////////////////////// PUBLIC API \\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\

    /**
     * Sets a given value for a given (private or protected) property on a given object
     *
     * @param object $subject
     * @param string $name
     * @param mixed $value
     */
    final public function setNonPublicProperty($subject, $name, $value)
    {
        $reflectionObject = new \ReflectionObject($subject);

        $properties = $this->getProperties($reflectionObject);

        // @FIXME: Use array_filter && array_pop to avoid problems with large property lists
        array_walk($properties, function (\ReflectionProperty $reflectionProperty) use ($subject, $name, $value) {
            if ($reflectionProperty->getName() === $name) {

                $reflectionProperty->setAccessible(true);
                $reflectionProperty->setValue($subject, $value);
                // @CHECKME: This could spell trouble for protected properties
                $reflectionProperty->setAccessible(false);
            }
        });

        // @TODO: Return current value of $reflectionProperty
    }

    ////////////////////////////// UTILITY METHODS \\\\\\\\\\\\\\\\\\\\\\\\\\\\\

    /**
     * @param $reflectionObject
     *
     * @return array
     */
    private function getProperties(\ReflectionObject $reflectionObject)
    {
        $properties = $reflectionObject->getProperties();

        $reflectionClass = $reflectionObject;

        while ($parent = $reflectionClass->getParentClass()) {
            $properties = array_merge($properties, $parent->getProperties());
            $reflectionClass = $parent;
        }

        return $properties;
    }
}

/*EOF*/
