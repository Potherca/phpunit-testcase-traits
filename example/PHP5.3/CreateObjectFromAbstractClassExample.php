<?php

namespace Potherca\PHPUnit\Example\CreateObjectFromAbstractClass;

abstract class AbstractExample
{
    abstract public function getName();

    public function sayName()
    {
        return $this->getName();
    }
}

class ExampleTest extends \PHPUnit\Framework\TestCase
{
    /**
     * As PHP5.3 does not support traits, __call is (a)bused instead of the trait.
     *
     use \Potherca\PhpUnit\CreateObjectFromAbstractClassTrait;
     *
     * @param string $name
     * @param array $parameters
     *
     * @return mixed
     */
    final public function __call($name, array $parameters)
    {
        require_once __DIR__.'/../../src/Shim/function.traitShim.php';

        return \Potherca\PhpUnit\Shim\traitShim($this, $name, $parameters);
    }

    const MOCK_VALUE = 'mock-value';

    public function testAbstractMethod()
    {
        $example = $this->createObjectFromAbstractClass(__NAMESPACE__.'\\AbstractExample');

        $expected = self::MOCK_VALUE;

        $example->method('getName')->willReturn($expected);

        $actual = $example->sayName();

        $this->assertEquals($expected, $actual);
    }
}
