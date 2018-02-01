<?php

/**
 * The `PHPUnit_Framework_TestCase` class  was added in PHPUnit v2.0 and
 * removed in PHPUnit v6.0, where it was replaced by the namespaced
 * `\PHPUnit\Framework\TestCase`.
 *
 * In order to allow developers to migrate their code before PHPUnit v6 was
 * released a "Forward Compatibility Layer" was added to PHPUnit v4.8 and v5.4
 * which also contained the `PHPUnit\Framework\TestCase` class.
 *
 * This means that for projects that use an older version of PHPUnit, the
 * namespaced class is not available and for newer projects the "underscored"
 * version is missing.
 *
 * This file makes sure that `\PHPUnit_Framework_TestCase` always exist.
 *
 * @codingStandardsIgnoreFile
 */
namespace {
    if (class_exists('\\PHPUnit\\Framework\\TestCase') === true
        && class_exists('\\PHPUnit_Framework_TestCase') === false
    ) {
        abstract class PHPUnit_Framework_TestCase extends \PHPUnit\Framework\TestCase {}
    }
}

/*EOF*/
