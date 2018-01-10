<?php

namespace Potherca\PHPUnit\Example\GetCompatibleExceptionName;

class Example
{
    public function __construct(array $value) {}
}

abstract class AbstractTestCase extends \PHPUnit\Framework\TestCase {}

class ExampleTest extends AbstractTestCase
{
    /**
     * As PHP5.3 does not support traits, __call is (a)bused instead of the trait.
     *
     use \Potherca\PhpUnit\GetCompatibleExceptionNameTrait;
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

    public function testException()
    {
        // Please not that `\TypeError::class` is NOT used, as this will cause an error if `TypeError` does not exist.
        $exceptionName = $this->getCompatibleExceptionName('\TypeError');

        if (method_exists($this, 'expectExceptionMessageRegExp')) {
            /* PHPUnit ^5.2 | ^6.0 */
            $this->expectException($exceptionName);
            $this->expectExceptionMessageRegExp('/none given|0 passed/');
        } else {
            /* PHPUnit ^4.3 | =< 5.6 */
            $this->setExpectedExceptionRegExp($exceptionName, '/none given|0 passed/');
        }

        $example = new Example();

        var_dump($example);
    }
}
