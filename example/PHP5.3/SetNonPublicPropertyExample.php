<?php

namespace Potherca\PHPUnit\Example\SetNonPublicProperty;

class Example
{
    private $name;

    public function getName()
    {
        return $this->name;
    }
}

class ExampleTest extends \PHPUnit\Framework\TestCase
{
    /**
     * As PHP5.3 does not support traits, __call is (a)bused instead of the trait.
     *
     use \Potherca\PhpUnit\SetNonPublicPropertyTrait;
     *
     * @param string $name
     * @param array $parameters
     *
     * @return mixed
     */
    final public function __call($name, array $parameters)
    {
        return \Potherca\PhpUnit\Shim\Util::traitShim($this, $name, $parameters);
    }

    const MOCK_VALUE = 'mock-value';

    public function testChangeHiddenProperty()
    {
        $example = new Example();

        $this->setNonPublicProperty($example, 'name', self::MOCK_VALUE);

        $expected = self::MOCK_VALUE;
        $actual = $example->getName();

        $this->assertEquals($expected, $actual);
    }
}
