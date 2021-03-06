<?php

namespace Potherca\PHPUnit\Example\GetNonPublicProperty;

class Example
{
    private $name;

    public function __construct($name)
    {
        $this->name = $name;
    }
}

class ExampleTest extends \PHPUnit\Framework\TestCase
{
    /**
     * As PHP5.3 does not support traits, __call is (a)bused instead of the trait.
     *
     use \Potherca\PhpUnit\Traits\GetNonPublicPropertyTrait;
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

    public function testReadHiddenProperty()
    {
        $example = new Example(self::MOCK_VALUE);

        $expected = self::MOCK_VALUE;
        $actual = $this->getNonPublicProperty($example, 'name');

        $this->assertEquals($expected, $actual);
    }
}
