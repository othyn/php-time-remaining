# PHP Time Remaining

[![Tests](https://github.com/othyn/php-time-remaining/actions/workflows/phpunit.yml/badge.svg)](https://github.com/othyn/php-time-remaining/actions/workflows/phpunit.yml)
[![Code Style](https://github.com/othyn/php-time-remaining/actions/workflows/phpcsfixer.yml/badge.svg)](https://github.com/othyn/php-time-remaining/actions/workflows/phpcsfixer.yml)
[![Downloads](https://img.shields.io/packagist/dt/othyn/php-time-remaining?color=green)](#installation)
[![GitHub license](https://img.shields.io/github/license/othyn/php-time-remaining)](https://github.com/othyn/php-time-remaining/blob/main/LICENSE)
[![Love](https://img.shields.io/badge/built%20with-love-red)](https://img.shields.io/badge/built%20with-love-red)

A [Composer](https://getcomposer.org/) package for [PHP](https://www.php.net/) that adds a seriously simple progress tracker, with a focus on estimating completion time.

```php
<?php

use Othyn\TimeRemaining\TimeRemaining;

$timeRemaining = new TimeRemaining(100);
sleep(30); // Simulate some work being done.

$formattedProgress = $timeRemaining->getFormattedProgress(50);
echo $formattedProgress; // Output: [50% - 50 / 100][~ 0h 0m 30s remaining]
```

The package is available on Packagist as [othyn/php-time-remaining](https://packagist.org/packages/othyn/php-time-remaining).

---

## Installation

Hop into your project that you wish to install it in and run the following Composer command to grab the latest version:

```sh
composer require othyn/php-time-remaining
```

---

## Usage

For more comprehensive usage examples, you can view the test suite. However I'll show some basic usage examples below.

### Initialisation

To start using the `TimeRemaining` package, initialise it with the total number of items required:

```php
<?php

require 'vendor/autoload.php';

use Othyn\TimeRemaining\TimeRemaining;

$totalItems = 100;
$timeRemaining = new TimeRemaining($totalItems);
```

You can also later update the total amount of items if you cannot define it at the point of initialisation:

```php
<?php

require 'vendor/autoload.php';

use Othyn\TimeRemaining\TimeRemaining;

$timeRemaining = new TimeRemaining();

// Code that fetches total items as $totalItems

$timeRemaining->setTotalItems($totalItems);
```

### Formatting the Output

To get a formatted string showing the progress and remaining time:

```php
<?php

$formattedProgress = $timeRemaining->getFormattedProgress($currentItem);
echo $formattedProgress; // Output: [50% - 50 / 100][~ 0h 0m 30s remaining]
```

You can also customise the format:

```php
<?php

$customFormat = '[%d%% - %d / %d items][~ %dh %dm %ds left]';
$formattedProgressCustom = $timeRemaining->getFormattedProgress($currentItem, $customFormat);
echo $formattedProgressCustom; // Output: [50% - 50 / 100 items][~ 0h 0m 30s left]
```

### Getting Elapsed Time

To get the elapsed time since the initialisation:

```php
<?php

$elapsedTime = $timeRemaining->getElapsedTime();
echo "Elapsed time: {$elapsedTime} seconds\n";
```

### Getting Progress

To get the progress based on the current item:

```php
<?php

$currentItem = 50;
$progress = $timeRemaining->getPercentageProgress($currentItem);
echo "Progress: {$progress}%\n";
```

### Getting Estimated Total Time

To get the estimated total time for the process:

```php
<?php

$estimatedTotalTime = $timeRemaining->getEstimatedTotalTime($currentItem);
echo "Estimated total time: {$estimatedTotalTime} seconds\n";
```

### Getting Remaining Time

To get the remaining time for the process:

```php
<?php

$remainingTime = $timeRemaining->getRemainingTime($currentItem);
echo "Remaining time: {$remainingTime} seconds\n";
```

---

## Development

Most development processes are wrapped up in an easy to use Docker container.

### Enforcing Style

The projects `.php-cs-fixer.dist.php` config contains the rules that this repo conforms to and will run against the `./src` and `./tests` directory.

For remote style enforcement there is a GitHub Action configured to automatically run `phpcsfixer`.

For local style enforcement there is a composer script `composer style` configured to run `phpcsfixer`.

### Testing

For remote testing there is a GitHub Action setup to automatically run the test suite on the `main` branch or and PR branches.

For local testing there is a Docker container that is pre-built that contains an Alpine CLI release of PHP + PHPUnit + xdebug. This is setup to test the project and can be setup via the following:

```sh
composer docker-build
```

This should trigger Docker Compose to build the image. You can then up the container via the following:

```sh
composer docker-up
```

There are tests for all code written, in which can be run via:

```sh
# PHPUnit with code coverage report
composer test

# PHPUnit with code coverage report, using local phpunit and xdebug
composer test-local
```

In those tests, there are Feature tests for a production ready implementation of the package. There are no Unit tests at present.

You can also easily open a shell in the testing container by using the command:

```sh
composer docker-shell
```
