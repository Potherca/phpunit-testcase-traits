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
    use \Potherca\PhpUnit\Traits\GetNonPublicPropertyTrait;

    const MOCK_VALUE = 'mock-value';

    public function testReadHiddenProperty()
    {
        $example = new Example(self::MOCK_VALUE);

        $expected = self::MOCK_VALUE;
        $actual = $this->getNonPublicProperty($example, 'name');

        $this->assertEquals($expected, $actual);
    }
}
