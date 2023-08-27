# Simple api client 

[![Latest Version on Packagist](https://img.shields.io/packagist/v/schenke-io/api-client.svg?style=flat-square)](https://packagist.org/packages/schenke-io/api-client)
[![GitHub Tests Action Status](https://img.shields.io/github/actions/workflow/status/schenke-io/api-client/run-tests.yml?branch=main&label=tests&style=flat-square)](https://github.com/schenke-io/api-client/actions?query=workflow%3Arun-tests+branch%3Amain)
[![GitHub Code Style Action Status](https://img.shields.io/github/actions/workflow/status/schenke-io/api-client/fix-php-code-style-issues.yml?branch=main&label=code%20style&style=flat-square)](https://github.com/schenke-io/api-client/actions?query=workflow%3A"Fix+PHP+code+style+issues"+branch%3Amain)
[![Total Downloads](https://img.shields.io/packagist/dt/schenke-io/api-client.svg?style=flat-square)](https://packagist.org/packages/schenke-io/api-client)

simple CURL based api client for applications without a SDK

## Installation 

```bash
composer require schenke-io/api-client
```

## Usage 

Build a local class which extends `BaseClient` or `BasejsonClient`.

```php
#app/MyClass.php 

class MyClass extends BaseClient {

    public function getAuthHeader(): array
    {
        return [
            'Authorization: Bearer <YOUR-TOKEN>'
        ];
    }
}

```

The use this class like this:
```php
#app/MyClass.php 

$api = new MyClass('https://example.com/api/v2/');
$result = $api->get('/users');
print_r($result);

```



## Testing

```bash
composer test
```

## Changelog

Please see [CHANGELOG](CHANGELOG.md) for more information on what has changed recently.


## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
