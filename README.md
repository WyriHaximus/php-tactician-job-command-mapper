# Job to Command mapper intended to be used with [`Tactician`](http://tactician.thephpleague.com/)

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

When creating a `Command` add the `@Job` annotation to map it to one or more jobs.

```php
<?php

namespace Test\App\Commands;

use WyriHaximus\Tactician\JobCommand\Annotations\Job;

/**
 * @Job("awesomesauce")
 * OR
 * @Job({"awesomesauce", "sauceawesome"})
 */
class AwesomesauceCommand
{
}
```

# Mapping

The mapper needs two things, a path where it can find commands. From there it scans all classes it finds for the `@Job` annotation and stores that map internally

## Mapper::map

To add a set of commands simple pass it the path and the mapper will pick up the correct namespaces for you.

```php
use League\Tactician\Setup\QuickStart;

$map = (new Mapper())->map('src' . DS . 'CommandBus');
```

## Mapper::hasCommand

Check if the map has a command for the job we have.

```php
$job = 'awesomesauce';
// True when it has a command or false when it doesn't
$map->hasCommand($job);
```

## Mapper::getCommand

Check if the map has a command for the job we have.

```php
$job = 'awesomesauce';
// Command class when it has a command, or throws an exception when it doesn't
$command = $map->getCommand($job);

// Call the command bus with the command we found to handle the given job
$commandBus->handle(new $command(...$data));
```

## Full class example

```php
final class CommandBusWrapper
{
    /**
      * @var CommandBus
      */
    private $commandBus;
    
    /**
      * @var Mapper
      */
    private $map;
    
    /**
      * @param CommandBus
      */
    public function __construct(CommandBus $commandBus)
    {
        $this->commandBus = $commandBus;
        $this->map = (new Mapper())->map('src' . DS . 'CommandBus', 'App\CommandBus');
    }
    
    /**
      * @param string $job
      * @param array $data
      * @return bool
      */
    function handle(string $job, array $data = []): bool
    {
        if (!$this->map->hasCommand($job)) {
            return false;
        }

        $command = $map->getCommand($job);
        $this->commandBus->handle(new $command(...$data));
        return true;
    }
}
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
