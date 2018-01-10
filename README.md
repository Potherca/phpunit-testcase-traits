# Traits for PHPUnit Testcases

[![project-stage-badge]][project-stage-page]
[![license-badge]][gpl-3]

> Traits that offer helper functions to be used in PHPUnit TestCases.

## Introduction

This projects contains various traits that offer convenience methods for tasks
that occur when creating test code.

## Installation

    composer require 'potherca/phpunit-testcase-traits'

## Usage

Add a `use` statement for a desired trait to a class definition. The public API
of that trait can then be used in the defined class.

For full details on how to use traits, please refer to [the section on traits in the PHP manual][php-traits].

### PHP 5.3 compatibility

Traits were not introduced until PHP5.4 so for older versions (i.e. PHP5.3) 
another way to load the trait's functionality is needed. A `traitShim` function 
is provided that can be used from a [magic `__call` method][__call-magic-method].

This is done by adding the following code to each (abstract base) test-case were
a Trait is to be used<sup>(1)</sup>:

```php

class ExampleTest extends \PHPUnit_Framework_TestCase
{
    // ....

    final public function __call($name, array $parameters)
    {
        return \Potherca\PhpUnit\Shim\Util::traitShim($this, $name, $parameters);
    }

    // ....
}
```

The public API of _all_ traits can then be used. 

In order to aid text-editors and IDEs in offering auto-completion, the following doc-block can be added to the
test-case class:

```php
/**
 * @method array[] createDataProvider(array $subject)
 * @method \PHPUnit_Framework_MockObject_MockObject | \PHPUnit\Framework\MockObject\MockObject createObjectFromAbstractClass($className)
 * @method string getCompatibleExceptionName($exceptionName)
 * @method void setDataProviderMaximumKeyLength($length)
 * @method void setDataProviderSortByKey($sort)
 * @method void setNonPublicProperty($subject, $name, $value)
 */
```

<sup>(1)</sup> Alternatively, the `src/Shim/function.traitShim.php` could be [loaded using composer's autoloader][composer-load-files].

## Available traits

- **CreateDataProviderTrait** -- _Create data-provider arrays._  
  Creates a `key/value` pair from a given one-dimensional array of values,
  which is meant to be returned from a data-provider method in a TestCase.

- **CreateObjectFromAbstractClassTrait** -- _Create an object instance from an abstract class._  
  Creates a concrete object whose methods can be called and, thus, be tested.
  
- **GetCompatibleExceptionNameTrait** -- _Provide names of PHP5 compatible `PHPUnit_Framework_Exception` for (new) PHP7 Exceptions._

- **SetNonPublicPropertyTrait** -- _Change the value of a non-public class properties._

Functioning usage examples are available in the [`example`](./example) directory. 
All examples can be run with `phpunit`. Simply use the `--config` flag to point 
to the desired config file (either `example-php-phpunit.xml` for the traits or 
`example-php53-phpunit.xml` for the PHP5.3 compatible Trait shims).

## Colophon

- **Author**: Created by [Potherca][potherca].
- **Homepage**: [packagist][packagist-page] / [git-repo][git-repo]
- **License**: Licensed under the  [GPL-3.0 license][gpl-3] (GNU General Public License v3.0)

[__call-magic-method]: http://php.net/manual/en/language.oop5.overloading.php#object.call
[composer-load-files]: https://getcomposer.org/doc/04-schema.md#files
[git-repo]: https://github.com/Potherca/phpunit-testcase-traits
[gpl-3]: ./LICENSE.md
[license-badge]: https://img.shields.io/badge/License-GPL--3.0-blue.svg
[packagist-page]: https://packagist.org/packages/potherca/phpunit-testcase-traits
[php-traits]: http://php.net/manual/en/language.oop5.traits.php
[potherca]: http://pother.ca/
[project-stage-badge]: http://img.shields.io/badge/Project%20Stage-Development-yellowgreen.svg
[project-stage-page]: http://bl.ocks.org/potherca/raw/a2ae67caa3863a299ba0/
