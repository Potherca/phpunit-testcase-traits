<?php

namespace Potherca\PhpUnit\Shim;

class GetCompatibleExceptionNameTest  extends AbstractTraitShimTest
{
    ////////////////////////////////// FIXTURES \\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\

    /////////////////////////////////// TESTS \\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\

    /** @noinspection PhpDocMissingThrowsInspection
     *
     * @dataProvider provideExpectedExceptions
     *
     * @param string $exceptionName
     * @param string|array $expected
     */
    final public function testShimShouldGetCompatibleExceptionNameWhenGivenExceptionName($exceptionName, $expected)
    {
        if (is_array($expected) === true) {
            /* Grab version specific value */
            $key = PHP_MAJOR_VERSION;
            if (array_key_exists(PHP_MAJOR_VERSION . PHP_MINOR_VERSION, $expected) === true) {
                $key = PHP_MAJOR_VERSION . PHP_MINOR_VERSION;
            }
            $expected = $expected[$key];
        }

        if (class_exists($expected) === false) {
            $expected = str_replace('_', '\\', $expected);
        }

        $mockTestCase = $this->getMockTestCase();

        $shim = new GetCompatibleExceptionName($mockTestCase);

        /** @noinspection PhpUnhandledExceptionInspection */
        $actual = $shim->getCompatibleExceptionName($exceptionName);

        $this->assertSame($expected, $actual);
    }

    ////////////////////////////// MOCKS AND STUBS \\\\\\\\\\\\\\\\\\\\\\\\\\\\\

    /////////////////////////////// DATAPROVIDERS \\\\\\\\\\\\\\\\\\\\\\\\\\\\\\

    public function provideExpectedExceptions()
    {
        return array(
            'ArgumentCountError' => array('\\ArgumentCountError', array(
                '5' => '\\PHPUnit_Framework_Error',
                '7' => '\\ArgumentCountError',
                '70' => '\\TypeError',
            )),
            'ArithmeticError' => array('\\ArithmeticError', array(
                '5' => '\\PHPUnit_Framework_Error',
                '7' => '\\ArithmeticError',
            )),
            'DivisionByZeroError' => array('\\DivisionByZeroError', '\\PHPUnit_Framework_Error_Warning'),
            'Exception' => array('\\Exception', '\\Exception'),
        );
    }

}
/*EOF*/
