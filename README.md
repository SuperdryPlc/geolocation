# Geolocation Class
[![Latest Stable Version](http://img.shields.io/packagist/v/superdry/geolocation.svg)](https://packagist.org/packages/superdry/geolocation)

## Table of Contents

- [Installation](#Installation)
- [Usage](#Usage)
- [Maintainers](#Maintainers)
- [License](#License)

## Installation

Install Composer
```sh
$ php -r "readfile('https://getcomposer.org/installer');" | php && php composer.phar install
```

```sh
$ composer require superdry/geolocation
```

Then include Composer's generated vendor/autoload.php to enable autoloading:


## Usage

```sh
require 'vendor/autoload.php';
use superdry\geolocation\GeolocationService;
```

### example

**getCoordinates**

> Get latitude/longitude coordinates from address.

``` php
$street = 'Koningin Maria Hendrikaplein';
$streetNumber = '1';
$city = 'Gent';
$zip = '1';
$country = 'belgium';
$geolocation = new GeolocationService();
$result = $geolocation->getCoordinates(
    $street,
    $streetNumber,
    $city,
    $zip,
    $country
);
```

**getAddress**

> Get address from latitude/longitude coordinates.

``` php
$latitude = 51.0363935;
$longitude = 3.7121008;
$geolocation = new GeolocationService();
$result = $geolocation->getAddress(
    $latitude,
    $longitude
);
```

## Maintainers

[@SuperdryPlc](https://github.com/SuperdryPlc).


## License
[MIT](LICENSE) © SuperdryPlc