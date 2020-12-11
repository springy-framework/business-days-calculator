# PHP Business Days Calculator

[![Latest Stable Version](https://poser.pugx.org/springy-framework/business-days-calculator/v/stable)](https://packagist.org/packages/springy-framework/business-days-calculator)
[![Build Status](https://travis-ci.org/springy-framework/business-days-calculator.svg?branch=main)](https://travis-ci.org/springy-framework/business-days-calculator)
![PHP Composer](https://github.com/springy-framework/business-days-calculator/workflows/PHP%20Composer/badge.svg)
[![PHPStan](https://img.shields.io/badge/PHPStan-enabled-brightgreen.svg?style=flat)](https://github.com/phpstan/phpstan)
[![Codacy Badge](https://app.codacy.com/project/badge/Grade/904f30bd1d82473a852af28384a915c8)](https://www.codacy.com/gh/springy-framework/business-days-calculator/dashboard?utm_source=github.com&amp;utm_medium=referral&amp;utm_content=springy-framework/business-days-calculator&amp;utm_campaign=Badge_Grade)
[![StyleCI](https://github.styleci.io/repos/318666163/shield?style=flat)](https://github.styleci.io/repos/318666163)
[![Total Downloads](https://poser.pugx.org/springy-framework/business-days-calculator/downloads)](https://packagist.org/packages/springy-framework/business-days-calculator)
[![License](https://poser.pugx.org/springy-framework/business-days-calculator/license)](https://packagist.org/packages/springy-framework/business-days-calculator)

This class can get the business days after today or a given date.

It can take as parameter a given date that will be taken as a reference.

The class can return one or more dates of the next business days after the given date considering the weekends and the regular holiday dates like Christmas, Easter, Corpus Christi, etc..

Optionally the class can also consider business days of specific countries like Brazil.

## Requirements

- PHP 7.3+

## Instalation

To get the latest stable version of this component use:

```json
"require": {
    "springy-framework/business-days-calculator": "*"
}
```

in your composer.json file.

## Usage

I suppose that the following example is all you need:

```php
<?php

require 'vendor/autoload.php'; // If you're using Composer (recommended)

// Using dynamic mode
$today = new DateTime();
$bdCalc = new Springy\BusinessDaysCalculator($today);
$newDate = $bdCalc->addBrazilianHolidays((int) $today->format('Y'))
    ->addBrazilianHolidays((int) $today->format('Y') + 1)
    ->addBusinessDays(20)
    ->getDate();
var_dump($newDate);

if ($bdCalc->isBusinessDay()) {
    echo "Is a business day\n";
}

// Getting nth business date in 'Y-m-d' format string without create an object
echo "The 20th business day from now is "
    . Springy\BusinessDaysCalculator::getBusinessDate(20)
    . "\n";

```

## Contributing

Please read our [contributing](/CONTRIBUTING.md) document and thank you for
doing that.

## Code of Conduct

In order to ensure that our community is welcoming to all, please review and
abide by the [code of conduct](/CODE_OF_CONDUCT.md).

## License

This project is licensed under [The MIT License (MIT)](/LICENSE).
