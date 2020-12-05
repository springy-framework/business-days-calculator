# Business Days Calculator PHP class

A simple PHP class to computes business days.

## Requirements

-   PHP 7.3+

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

## License

This project is licensed under [The MIT License (MIT)](/LICENSE).

