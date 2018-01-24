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
    use \Potherca\PhpUnit\Traits\CreateObjectFromAbstractClassTrait;

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
