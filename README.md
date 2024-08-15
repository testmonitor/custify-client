# TestMonitor Custify Client

[![Latest Stable Version](https://poser.pugx.org/testmonitor/custify-client/v/stable)](https://packagist.org/packages/testmonitor/custify-client)
[![CircleCI](https://img.shields.io/circleci/project/github/testmonitor/custify-client.svg)](https://circleci.com/gh/testmonitor/custify-client)
[![StyleCI](https://styleci.io/repos/342627557/shield)](https://styleci.io/repos/342627557)
[![codecov](https://codecov.io/gh/testmonitor/custify-client/graph/badge.svg?token=V3VTFX5EAP)](https://codecov.io/gh/testmonitor/custify-client)
[![License](https://poser.pugx.org/testmonitor/custify-client/license)](https://packagist.org/packages/testmonitor/custify-client)

This package provides a very basic, convenient, and unified wrapper for [Custify](https://docs.custify.com/).

## Table of Contents

- [Installation](#installation)
- [Usage](#usage)
- [Examples](#examples)
- [Tests](#tests)
- [Changelog](#changelog)
- [Contributing](#contributing)
- [Credits](#credits)
- [License](#license)

## Installation

To install the client you need to require the package using composer:

	$ composer require testmonitor/custify-client

Use composer's autoload:

```php
require __DIR__.'/../vendor/autoload.php';
```

You're all set up now!

## Usage

You'll have to instantiate the client using your credentials:

```php
$custify = new \TestMonitor\Custify\Client('token');
```

Next, you can start interacting with Custify.

## Examples

Get a list of Custify people:

```php
$people = $custify->people();
```

Create a new person:

```php
$person = $custify->createPerson(new \TestMonitor\Custify\Resources\Person([
    'user_id' => 25,
    'email' => 'john.doe@mail.com',
    'phone' => '+44 7911 123456',
    'name' => 'John Doe',
]);
```

## Tests

The package contains integration tests. You can run them using PHPUnit.

    $ vendor/bin/phpunit

## Changelog

Refer to [CHANGELOG](CHANGELOG.md) for more information.

## Contributing

Refer to [CONTRIBUTING](CONTRIBUTING.md) for contributing details.

## Credits

* **Thijs Kok** - *Lead developer* - [ThijsKok](https://github.com/thijskok)
* **Stephan Grootveld** - *Developer* - [Stefanius](https://github.com/stefanius)
* **Frank Keulen** - *Developer* - [FrankIsGek](https://github.com/frankisgek)
* **Muriel Nooder** - *Developer* - [ThaNoodle](https://github.com/thanoodle)

## License

The MIT License (MIT). Refer to the [License](LICENSE.md) for more information.
