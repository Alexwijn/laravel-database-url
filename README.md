## Laravel Database URL
[![Packagist License](https://poser.pugx.org/alexwijn/laravel-database-url/license.png)](http://choosealicense.com/licenses/mit/)
[![Latest Stable Version](https://poser.pugx.org/alexwijn/laravel-database-url/version.png)](https://packagist.org/packages/alexwijn/laravel-database-url)
[![Total Downloads](https://poser.pugx.org/alexwijn/laravel-database-url/d/total.png)](https://packagist.org/packages/alexwijn/laravel-database-url)

This package will provide automatically parse and configure you Laravel application to use the custom environment variables that commonly used for services like Heroku and Docker. 

## Installation

Require this package with composer. It is recommended to only require the package for development.

```shell
composer require alexwijn/laravel-database-url
```

Laravel 5.5 uses Package Auto-Discovery, so doesn't require you to manually add the ServiceProvider.

### Laravel 5.5+:

If you don't use auto-discovery, add the ServiceProvider to the providers array in config/app.php

```php
Alexwijn\DatabaseUrl\ServiceProvider::class,
```

## Supported environment variables

At the moment we support the following configurations:

- *DATABASE_URL* - Eg. mysql://username:password@localhost/database
- *DATABASE_URL* - Eg. postgres://username:password@localhost/database
- *REDIS_URL* - Eg. redis://redis:password@localhost/0
