# Job to Command mapper for [`Tactician`](http://tactician.thephpleague.com/)

[![Build Status](https://travis-ci.org/WyriHaximus/php-tactician-job-command-mapper.svg?branch=master)](https://travis-ci.org/WyriHaximus/php-tactician-job-command-mapper)
[![Latest Stable Version](https://poser.pugx.org/WyriHaximus/tactician-job-command-mapper/v/stable.png)](https://packagist.org/packages/WyriHaximus/tactician-job-command-mapper)
[![Total Downloads](https://poser.pugx.org/WyriHaximus/tactician-job-command-mapper/downloads.png)](https://packagist.org/packages/WyriHaximus/tactician-job-command-mapper/stats)
[![Code Coverage](https://scrutinizer-ci.com/g/WyriHaximus/php-tactician-job-command-mapper/badges/coverage.png?b=master)](https://scrutinizer-ci.com/g/WyriHaximus/php-tactician-job-command-mapper/?branch=master)
[![License](https://poser.pugx.org/WyriHaximus/tactician-job-command-mapper/license.png)](https://packagist.org/packages/WyriHaximus/tactician-job-command-mapper)
[![PHP 7 ready](http://php7ready.timesplinter.ch/WyriHaximus/php-tactician-job-command-mapper/badge.svg)](https://travis-ci.org/WyriHaximus/php-tactician-job-command-mapper)


# Install

To install via [Composer](http://getcomposer.org/), use the command below, it will automatically detect the latest version and bind it with `^`.

```
composer require wyrihaximus/tactician-job-command-mapper
```

# Set up

When creating a `Command` add the `@Handler` annotation to map it to a `Handler`.

```php
<?php

namespace Test\App\Commands;

use WyriHaximus\Tactician\JobCommand\Annotations\Job;

/**
 * @Job("awesomesauce")
 */
class AwesomesauceCommand
{
    /**
     * @var string
     */
    private $value;

    /**
     * AwesomesauceCommand constructor.
     * @param string $value
     */
    public function __construct($value)
    {
        $this->value = $value;
    }

    /**
     * @return string
     */
    public function getValue()
    {
        return $this->value;
    }
}
```

# Mapping

The mapper needs two things, a path where it can find commands, and the corrosponding namespace for that path. From there it scans all classes it finds for the `@Handler` annotation ad returns a map of commands and handlers that match.

## Mapper::mapInstantiated

For when you want to get started quickly and non of you handlers need to get dependencies injected.

```php
use League\Tactician\Setup\QuickStart;
use WyriHaximus\Tactician\CommandHandler\Mapper;

$commandBus = QuickStart::create(
    Mapper::mapInstanciated('src' . DS . 'CommandBus', 'App\CommandBus')
);
```

## Mapper::map

For when you don't want a set instanciated handlers, for exampe useful when using [`league/tactician-container`](http://tactician.thephpleague.com/plugins/container/) for automatic dependency injection.

```php
use League\Tactician\Setup\QuickStart;

$commandToHandlerMap = Mapper::map('src' . DS . 'CommandBus', 'App\CommandBus');
```

# License

The MIT License (MIT)

Copyright (c) 2017 Cees-Jan Kiewiet

Permission is hereby granted, free of charge, to any person obtaining a copy
of this software and associated documentation files (the "Software"), to deal
in the Software without restriction, including without limitation the rights
to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
copies of the Software, and to permit persons to whom the Software is
furnished to do so, subject to the following conditions:

The above copyright notice and this permission notice shall be included in all
copies or substantial portions of the Software.

THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE
SOFTWARE.
