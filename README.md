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

## Available traits

- **CreateDataProviderTrait** -- _Create data-provider arrays._  
  Creates a `key/value` pair from a given one-dimensional array of values,
  which is meant to be returned from a data-provider method in a TestCase.

- **CreateObjectFromAbstractClassTrait** -- _Create an object instance from an abstract class._  
  Creates a concrete object whose methods can be called and, thus, be tested.
  
- **GetCompatibleExceptionNameTrait** -- _Provide names of PHP5 compatible `PHPUnit_Framework_Exception` for (new) PHP7 Exceptions._

- **SetNonPublicPropertyTrait** -- _Change the value of a non-public class properties._


## Colophon

- **Author**: Created by [Potherca][potherca].
- **License**: Licensed under the  [GPL-3.0 license][gpl-3] (GNU General Public License v3.0)

[gpl-3]: ./LICENSE.md
[license-badge]: https://img.shields.io/badge/License-GPL--3.0-blue.svg
[php-traits]: http://php.net/manual/en/language.oop5.traits.php
[potherca]: http://pother.ca/
[project-stage-badge]: http://img.shields.io/badge/Project%20Stage-Development-yellowgreen.svg
[project-stage-page]: http://bl.ocks.org/potherca/raw/a2ae67caa3863a299ba0/
