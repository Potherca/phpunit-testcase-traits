<?php

namespace Potherca\PHPUnit\Example\CreateDataProvider;

class ExampleTest extends \PHPUnit\Framework\TestCase
{
    use \Potherca\PhpUnit\CreateDataProviderTrait;

    /** @var array */
    private $values = array(
        -1,
        0,
        1,
        true,
        false,
        null,
        [null],
        [],
    );

    /**
     * @dataProvider provideValues
     */
    final public function testDataProvider($actual)
    {
        $expected = $this->values;

        $this->assertContains($actual, $expected);
    }

    final public function provideValues()
    {
        return $this->createDataProvider($this->values);
    }
}
