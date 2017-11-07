<?php

namespace Potherca\PhpUnit\Shim;

class GetNonPublicProperty extends AbstractTraitShim
{

    private $reflectionObjects = array();

    //////////////////////////////// PUBLIC API \\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\

    /**
     * Sets a given value for a given (private or protected) property on a
     * given object
     *
     * @param object $subject
     * @param string $name
     *
     * @return mixed
     *
     * @throws \InvalidArgumentException
     */
    final public function getNonPublicProperty($subject, $name)
    {
        $reflectionProperty = $this->getPropertyFromObject($subject, $name);

        return $reflectionProperty->getValue($subject);
    }

    /**
     * @param object $subject
     * @param string $name
     *
     * @return \ReflectionProperty
     *
     * @throws \InvalidArgumentException
     */
    final public function getPropertyFromObject($subject, $name)
    {
        $properties = $this->getPropertiesFromObject($subject);

        $properties = array_filter($properties, function (\ReflectionProperty $reflectionProperty) use ($name) {
            return $reflectionProperty->getName() === $name;
        });

        $reflectionProperty = array_shift($properties);

        if ($reflectionProperty === null) {
            $message = vsprintf('Could not find non-public property "%s" in "%s"', array($name, get_class($subject)));
            throw new \InvalidArgumentException($message);
        }

        $reflectionProperty->setAccessible(true);

        return $reflectionProperty;
    }

    ////////////////////////////// UTILITY METHODS \\\\\\\\\\\\\\\\\\\\\\\\\\\\\

    /**
     * @param object $subject
     *
     * @return \ReflectionProperty[]
     */
    private function getPropertiesFromObject($subject)
    {
        $objectHash = spl_object_hash($subject);

        if (array_key_exists($objectHash, $this->reflectionObjects) === false) {
            $reflectionObject = new \ReflectionObject($subject);

            $this->reflectionObjects[$objectHash] = $this->getProperties($reflectionObject);
        }

        return $this->reflectionObjects[$objectHash];
    }

    /**
     * @param \ReflectionObject $reflectionObject
     *
     * @return \ReflectionProperty[]
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
