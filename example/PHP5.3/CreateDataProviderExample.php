<?php

namespace Potherca\PHPUnit\Example\CreateDataProvider;

class ExampleTest extends \PHPUnit\Framework\TestCase
{
    /**
     * As PHP5.3 does not support traits, __call is (a)bused instead of the trait.
     *
     use \Potherca\PhpUnit\Traits\CreateDataProviderTrait;
     *
     * @param string $name
     * @param array $parameters
     *
     * @return mixed
     *
     * @method setNonPublicProperty($subject, $name, $value)
     */
    final public function __call($name, array $parameters)
    {
        return \Potherca\PhpUnit\Shim\Util::traitShim($this, $name, $parameters);
    }

    /** @var array */
    private $values = array(
        -1,
        0,
        1,
        true,
        false,
        null,
        array(null),
        array(),
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
