<?php

namespace Potherca\PhpUnit\Shim;

class GetCompatibleExceptionNameTest  extends AbstractTraitShimTest
{
    ////////////////////////////////// FIXTURES \\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\

    /////////////////////////////////// TESTS \\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\

    /** @noinspection PhpDocMissingThrowsInspection
     *
     * @dataProvider provideExpectedExceptionsWithoutContext
     *
     * @param string $exceptionName
     * @param string|array $expected
     */
    final public function testShimShouldGetCompatibleExceptionNameWhenGivenExceptionNameWithoutContext($exceptionName, $expected)
    {
        $expected = $this->getExpectedExceptionFromData($expected);

        $mockTestCase = $this->getMockTestCase();

        $shim = new GetCompatibleExceptionName($mockTestCase);

        /** @noinspection PhpUnhandledExceptionInspection */
        $actual = $shim->getCompatibleExceptionName($exceptionName);

        $this->assertSame($expected, $actual);
    }

    /** @noinspection PhpDocMissingThrowsInspection
     *
     * @dataProvider provideExpectedExceptionsWithContext
     *
     * @param string $exceptionName
     * @param string|array $expected
     */
    final public function testShimShouldComplainWhenGivenExceptionNameWithContext($exceptionName, $expected)
    {
        $mockTestCase = $this->getMockTestCase();

        $shim = new GetCompatibleExceptionName($mockTestCase);

        $currentVersion = PHP_MAJOR_VERSION . PHP_MINOR_VERSION;

        if (array_key_exists($currentVersion, $expected) === false) {
            $this->markTestSkipped('Context not needed for PHP'.$currentVersion);
        }

        $exception = '\\PHPUnit_Framework_Exception';

        if (class_exists($exception) === false) {
            $exception = str_replace('_', '\\', $exception);
        }

        $regex = '/' . vsprintf(GetCompatibleExceptionName::ERROR_CONTEXT_NEEDED, array('.*', '.*')) . '/';
        if (method_exists($this, 'expectExceptionMessageRegExp')) {
            /* PHPUnit ^5.2 | ^6.0 */
            $this->expectExceptionMessageRegExp($regex);
            $this->expectException($exception);
        } else {
            /* PHPUnit ^4.3 | =< 5.6 */
            $this->setExpectedExceptionRegExp($exception, $regex);
        }

        /** @noinspection PhpUnhandledExceptionInspection */
        $actual = $shim->getCompatibleExceptionName($exceptionName);
    }

    /** @noinspection PhpDocMissingThrowsInspection
     *
     * @dataProvider provideExpectedExceptionsWithContext
     *
     * @param string $exceptionName
     * @param string|array $expected
     * @param string $context
     */
    final public function testShimShouldGetCompatibleExceptionNameWhenGivenExceptionNameWithContext($exceptionName, $expected, $context)
    {
        $expected = $this->getExpectedExceptionFromData($expected);

        $mockTestCase = $this->getMockTestCase();

        $shim = new GetCompatibleExceptionName($mockTestCase);

        /** @noinspection PhpUnhandledExceptionInspection */
        $actual = $shim->getCompatibleExceptionName($exceptionName, $context);

        $this->assertSame($expected, $actual);
    }

    ////////////////////////////// MOCKS AND STUBS \\\\\\\\\\\\\\\\\\\\\\\\\\\\\

    /////////////////////////////// DATAPROVIDERS \\\\\\\\\\\\\\\\\\\\\\\\\\\\\\

    public function provideExpectedExceptionsWithContext()
    {
        return array(
            'ArgumentCountError:with type-hint' => array(
                '\\ArgumentCountError',
                array(
                    '5' => '\\PHPUnit_Framework_Error',
                    '7' => '\\ArgumentCountError',
                    '70' => '\\TypeError',
                ),
                GetCompatibleExceptionName::ARGUMENT_COUNT_ERROR_WITH_TYPE_HINT,
            ),
            'ArgumentCountError:without type-hint' => array(
                '\\ArgumentCountError',
                array(
                    '5' => '\\PHPUnit_Framework_Error',
                    '7' => '\\ArgumentCountError',
                    '70' => '\\PHPUnit_Framework_Error',
                ),
                GetCompatibleExceptionName::ARGUMENT_COUNT_ERROR_WITHOUT_TYPE_HINT,
            ),
        );
    }

    public function provideExpectedExceptionsWithoutContext()
    {
        return array(
            'ArithmeticError' => array('\\ArithmeticError', array(
                '5' => '\\PHPUnit_Framework_Error',
                '7' => '\\ArithmeticError',
            )),
            'DivisionByZeroError' => array('\\DivisionByZeroError', '\\PHPUnit_Framework_Error_Warning'),
            'Exception' => array('\\Exception', '\\Exception'),
            'ParseError' => array('\\ParseError', '\\ParseError'),
        );
    }

    /**
     * @param $expected
     *
     * @return mixed
     */
    private function getExpectedExceptionFromData($expected)
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

            if ($expected === '\\PHPUnit\\Framework\\Error') {
                $expected = '\\PHPUnit\\Framework\\Error\\Error';
            }
        }

        return $expected;
    }
}
/*EOF*/
