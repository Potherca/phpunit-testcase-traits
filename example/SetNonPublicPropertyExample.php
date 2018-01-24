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
    use \Potherca\PhpUnit\Traits\SetNonPublicPropertyTrait;

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
