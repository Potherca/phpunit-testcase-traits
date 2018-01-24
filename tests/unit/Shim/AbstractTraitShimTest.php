<?php

namespace Potherca\PhpUnit\Shim;

use Potherca\PhpUnit\AbstractTestCase;

/*/ Mock function from global scope. Ugly but functional /*/
$container = new \stdClass();
$container->args = null;
    function call_user_func_array($function, $params) {
    global $container;

    $container->args = func_get_args();
}

/**
 * Tests for the AbstractTraitShim class
 *
 * @coversDefaultClass \Potherca\PhpUnit\Shim\AbstractTraitShim
 * @covers ::<!public>
 */
abstract class AbstractTraitShimTest extends AbstractTestCase
{
    /////////////////////////////////// TESTS \\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\

    /**
     * @coversNothing
     */
    final public function testShimShouldComplainWhenInstantiatedWithoutAnything()
    {
        list($function, $expectedException, $expectedExceptionMessage) = $this->createMissingArgumentExpectation(1);

        $this->{$function}($expectedException, $expectedExceptionMessage);

        /** @noinspection PhpParamsInspection */
        new GetCompatibleExceptionName();
    }

    /**
     * @covers \Potherca\PhpUnit\Shim\AbstractTraitShim::__construct
     */
    final public function testShimShouldComplainWhenInstantiatedWithoutTestCase()
    {
        $expectedException = '\\Potherca\\PhpUnit\\InvalidArgumentException';
        $expectedExceptionMessage = 'Argument 1 passed to Potherca\PhpUnit\Shim\AbstractTraitShim::__construct must be an instance of "\PHPUnit_Framework_TestCase" or "\PHPUnit\Framework\TestCase", stdClass given';

        $function = $this->getExpectExpectationMethodName();

        $this->{$function}($expectedException, $expectedExceptionMessage);

        new GetCompatibleExceptionName(new \stdClass());
    }

    /**
     * @covers \Potherca\PhpUnit\Shim\AbstractTraitShim::__construct
     *
     * @return array
     */
    final public function testShimShouldBeInstantiatedWhenGivenTestCase()
    {
        $calledClass = get_called_class();
        $calledClass = substr($calledClass, 0, -4); // 4 = strlen('Test')

        $mockName = class_exists('\PHPUnit_Framework_TestCase')
            ? '\PHPUnit_Framework_TestCase'
            : '\PHPUnit\Framework\TestCase'
        ;

        $mockTestCase = $this->getMockBuilder($mockName)->disableOriginalConstructor()->getMockForAbstractClass();

        $shim = new $calledClass($mockTestCase);

        $this->assertInstanceOf($calledClass, $shim);

        return array(
            'mock-test-case' => $mockTestCase,
            'shim' => $shim,
        );
    }

    /**
     * @param array $param
     *
     * @return array
     *
     * @covers \Potherca\PhpUnit\Shim\AbstractTraitShim::getTestcase
     *
     * @depends testShimShouldBeInstantiatedWhenGivenTestCase
     */
    final public function testShimShouldReturnGivenTestcaseWhenAskedToGetTestCase(array $param)
    {
        $actual = $param['shim']->getTestcase();
        $expected = $param['mock-test-case'];

        $this->assertSame($actual, $expected);

        return $param;
    }

    /**
     * @param array $param
     *
     * @covers \Potherca\PhpUnit\Shim\AbstractTraitShim::__invoke
     *
     * @depends testShimShouldReturnGivenTestcaseWhenAskedToGetTestCase
     */
    final public function testShimShouldComplainWhenInvokedWithoutParameters(array $param)
    {
        list($function, $expectedException, $expectedExceptionMessage) = $this->createWrongArgumentTypeExpectation('array');

        $this->{$function}($expectedException, $expectedExceptionMessage);

        $actual = $param['shim']();
    }

    /**
     * @param array $param
     *
     * @covers \Potherca\PhpUnit\Shim\AbstractTraitShim::__invoke
     *
     * @depends testShimShouldReturnGivenTestcaseWhenAskedToGetTestCase
     */
    final public function testShimShouldMethodWithSameNameAsShimWhenInvokedWithParameters(array $param)
    {
        global $container;

        /* Setup test */
        $shim = $param['shim'];
        $mockParameter = array('mock-key' => 'mock-value');

        /* Call system under test */
        $shim($mockParameter);

        /* Set up expectations */
        $parts = explode('\\', get_class($shim));
        $expectedMethod = array_pop($parts);

        $expected = array(
            array($shim, $expectedMethod),
            $mockParameter,
        );

        $actual = $container->args;

        $this->assertSame($expected, $actual);
    }

    final public function testShim_Should_When_GetExistingClassName()
    {
        $this->markTestIncomplete('@TODO: Write tests for \Potherca\PhpUnit\Shim\AbstractTraitShim::getExistingClassName()');
    }
}

/*EOF*/
