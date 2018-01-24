<?php

namespace Potherca\PhpUnit\Shim;

use Potherca\PhpUnit\InvalidArgumentException;

interface TraitShimInterface
{
    /**
     * @return \PHPUnit\Framework\TestCase|\PHPUnit_Framework_TestCase
     */
    public function getTestcase();

    /**
     * @param $testcase \PHPUnit\Framework\TestCase | \PHPUnit_Framework_TestCase
     *
     * @throws InvalidArgumentException
     */
    public function __construct($testcase);

    /**
     * @param array $parameter
     *
     * @return mixed
     */
    public function __invoke(array $parameter);

    /**
     * Get the namespaced or non-namespaced name of a given class, depending on
     * which one exists
     *
     * @param string $class
     *
     * @return string
     *
     * @throws InvalidArgumentException
     */
    public function getExistingClassName($class);
}

/*EOF*/
