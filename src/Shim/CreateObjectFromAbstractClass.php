<?php

namespace Potherca\PhpUnit\Shim;

use PHPUnit_Framework_MockObject_MockObject as MockObject;

/**
 * @method fail($message)
 * @method \PHPUnit_Framework_MockObject_MockBuilder getMockBuilder($className)
 */
class CreateObjectFromAbstractClass extends AbstractTraitShim
{
    //////////////////////////////// PUBLIC API \\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\

    /**
     * @param string $className
     *
     * @param array|null $arguments
     *
     * @return MockObject
     *
     * @throws \PHPUnit_Framework_AssertionFailedError|\PHPUnit\Framework\AssertionFailedError
     */
    final public function createObjectFromAbstractClass($className, array $arguments = null)
    {
        $this->validateClassExists($className);
        $this->validateClassIsAbstract($className);

        $mockBuilder = $this->getTestcase()->getMockBuilder($className);

        if (is_array($arguments)) {
            $mockBuilder->setConstructorArgs($arguments);
        } else {
            $mockBuilder->disableOriginalConstructor();
        }

        return $mockBuilder->getMockForAbstractClass();
    }

    ////////////////////////////// UTILITY METHODS \\\\\\\\\\\\\\\\\\\\\\\\\\\\\

    /**
     * @param string $className
     *
     * @throws \PHPUnit_Framework_AssertionFailedError|\PHPUnit\Framework\AssertionFailedError
     */
    private function validateClassExists($className)
    {
        if (class_exists($className) === false) {
            $message = vsprintf('Can not create class "%s". No such class exists', array($className));
            $this->getTestcase()->fail($message);
        }
    }

    /**
     * @param string $className
     *
     * @throws \PHPUnit_Framework_AssertionFailedError|\PHPUnit\Framework\AssertionFailedError
     */
    private function validateClassIsAbstract($className)
    {
        if (is_callable(array($className, '__construct')) === true) {
            $message = vsprintf('Can not create class "%s". Class exists but is not abstract', array($className));
            $this->getTestcase()->fail($message);
        }
    }
}

/*EOF*/
