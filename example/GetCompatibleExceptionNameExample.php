<?php

namespace Potherca\PHPUnit\Example\GetCompatibleExceptionName;

class Example
{
    public function __construct(array $value) {}
}

abstract class AbstractTestCase extends \PHPUnit\Framework\TestCase {}

class ExampleTest extends AbstractTestCase
{
    use \Potherca\PhpUnit\Traits\GetCompatibleExceptionNameTrait;

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
