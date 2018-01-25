<?php

namespace Potherca\PhpUnit\Traits;

use Potherca\PhpUnit\AbstractTestCase;

/**
 * Test for the GetCompatibleExceptionNameTrait
 *
 * !!! Please make EXTRA sure no functionality from this library is used in tests !!!
 *
 * @requires PHP 5.4
 * @coversDefaultClass \Potherca\PhpUnit\Traits\GetCompatibleExceptionNameTrait
 */
class GetCompatibleExceptionNameTraitTest extends AbstractTestCase
{
    /////////////////////////////////// TESTS \\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\

    /**
     * @covers ::getCompatibleExceptionName
     */
    final public function testTraitShouldComplainWhenNotGivenExceptionName()
    {
        $trait = $this->getMockForTrait('\\Potherca\\PhpUnit\\Traits\\GetCompatibleExceptionNameTrait');

        list($function, $expectedException, $expectedExceptionMessage) = $this->createMissingArgumentExpectation(1);

        $this->{$function}($expectedException, $expectedExceptionMessage);

        /** @noinspection PhpUndefinedMethodInspection */
        $trait->getCompatibleExceptionName();
    }

    /**
     * @covers ::getCompatibleExceptionName
     *
     * @uses \Potherca\PhpUnit\Shim\AbstractTraitShim
     * @uses \Potherca\PhpUnit\Shim\Util
     */
    final public function testTraitShouldCallUtilShimWhenGivenExceptionName()
    {
        $trait = $this->getMockForTrait('\\Potherca\\PhpUnit\\Traits\\GetCompatibleExceptionNameTrait');

        $expectedException = '\\Potherca\\PhpUnit\\InvalidArgumentException';
        $expectedExceptionMessage = 'Argument 1 passed to Potherca\PhpUnit\Shim\AbstractTraitShim::__construct must be an instance of "\PHPUnit_Framework_TestCase" or "\PHPUnit\Framework\TestCase", Mock_Trait_GetCompatibleExceptionNameTrait';

        $function = $this->getExpectExpectationMethodName();

        $this->{$function}($expectedException, $expectedExceptionMessage);

        $trait->getCompatibleExceptionName('Mock Exception Name');
    }
}

/*EOF*/
