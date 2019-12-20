[![Build Status](https://travis-ci.org/wyrfel/php-callback.svg?branch=master)](https://travis-ci.org/wyrfel/php-callback)
[![Maintainability](https://api.codeclimate.com/v1/badges/54e9839e6a3efff7cc07/maintainability)](https://codeclimate.com/github/wyrfel/php-callback/maintainability)
[![Test Coverage](https://api.codeclimate.com/v1/badges/54e9839e6a3efff7cc07/test_coverage)](https://codeclimate.com/github/wyrfel/php-callback/test_coverage)
[![Latest Stable Version](https://poser.pugx.org/wyrfel/php-callback/version?format=flat)](https://packagist.org/packages/wyrfel/php-callback)
[![Total Downloads](https://poser.pugx.org/wyrfel/php-callback/downloads?format=flat)](https://packagist.org/packages/wyrfel/php-callback)
[![License](https://poser.pugx.org/wyrfel/php-callback/license?format=flat)](https://packagist.org/packages/wyrfel/php-callback)

# php-callback

This is a simple callable helper that allows better control over the call
parameters on the final call.

## Concepts
As opposed to an immediate function call, that accepts one set of parameters,
when passing a callback, there are two occasions at which parameters may be
passed - once at the time the callback is created/passed, the other at the
time of the call.

We will call the former the **instantiation arguments** as they are passed
at the time of the instantiation of the callback helper - and we'll call
the latter the **invocation arguments** as they are passed at the time of
invocation of the callback.

With a regular php callable, parameters can only be passed at the time of
the call. An exception is the `array_walk` built-in that allows the
specification of a userdata parameter when passing the callback that is
then appended to the call parameters at the time of the call.
This helper does the same thing, but with a few more capabilities.

When there are two sets of parameters, but the callable can of course only
accept one set of parameters, the question arises how to merge the two
sets into one. This helper is configurable to
- append the invocation arguments to the instantiation arguments
`$callable(A1, A2, A3, B1, B2)`
- prepend the invocation arguments to the instantiation arguments
`$callable(B1, B2, A1, A2, A3)`
- ignore the invocation arguments altogether
`$callable(A1, A2, A3)`
- override the instantiation arguments with the invocation arguments
based on position
`$callable(B1, B2, A3)`

(with An begin the instantiation arguments an Bn being the invocation arguments)

## Usage
```php
$callback = new Callback([$this, 'methodName'], $withThis, $andThat);

$callback->appendInvocationArguments();
$callback->prependInvocationArguments();
$callback->ignoreInvocationArguments();
$callback->overrideInstantiationArguments();

preg_replace_callback($regexp, $callback, $subject);
```
