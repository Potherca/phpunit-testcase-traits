<?php

namespace Potherca\PhpUnit\Shim;

abstract class AbstractTraitShim
{
    ////////////////////////////// CLASS PROPERTIES \\\\\\\\\\\\\\\\\\\\\\\\\\\\

    /** @var \PHPUnit\Framework\TestCase | \PHPUnit_Framework_TestCase */
    private $testcase;

    //////////////////////////// SETTERS AND GETTERS \\\\\\\\\\\\\\\\\\\\\\\\\\\

    /**
     * @return \PHPUnit\Framework\TestCase|\PHPUnit_Framework_TestCase
     */
    public function getTestcase()
    {
        return $this->testcase;
    }

    //////////////////////////////// PUBLIC API \\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\

    /**
     * @param $testcase \PHPUnit\Framework\TestCase | \PHPUnit_Framework_TestCase
     *
     * @throws \InvalidArgumentException
     */
    final public function __construct($testcase)
    {
        if ($testcase instanceof \PHPUnit\Framework\TestCase === false &&
            $testcase instanceof \PHPUnit_Framework_TestCase === false
        ) {
            $type = gettype($testcase);

            if ($type === 'object') {
                $type = get_class($testcase);
            }

            $message = vsprintf(
                'Argument 1 passed to %s must be an instance of %s, %s given',
                array(
                    __METHOD__,
                    '"\PHPUnit_Framework_TestCase" or "\PHPUnit\Framework\TestCase"',
                    $type,
                )
            );
            throw new \InvalidArgumentException($message);
        }

        $this->testcase = $testcase;
    }

    final public function __invoke(array $parameter)
    {
        $class = get_class($this);
        $parts = explode('\\', $class);
        $function = array_pop($parts);

        return call_user_func_array(array($this, $function), $parameter);
    }
}

/*EOF*/
