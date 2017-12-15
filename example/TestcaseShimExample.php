<?php

namespace Potherca\PHPUnit\Example\TestcaseShim;

class NamespacedExampleTest extends \PHPUnit\Framework\TestCase
{
    final public function testNamespacedTestcaseCanBeExtended()
    {
        $this->assertTrue(true);
    }
}

class UnderscoredExampleTest extends \PHPUnit_Framework_TestCase
{
    final public function testUnderscoredTestcaseCanBeExtended()
    {
        $this->assertTrue(true);
    }
}
