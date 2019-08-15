## Tidy AMQP

Tidy RabbitMQ AMQP Library

![Packagist Version](https://img.shields.io/packagist/v/kain/tidy-amqp.svg?style=flat-square)
![Packagist](https://img.shields.io/packagist/dt/kain/tidy-amqp.svg?color=blue&style=flat-square)
![PHP from Packagist](https://img.shields.io/packagist/php-v/kain/tidy-amqp.svg?color=blue&style=flat-square)
![Packagist](https://img.shields.io/packagist/l/kain/tidy-amqp.svg?color=blue&style=flat-square)

#### Setup

```shell
composer require kain/tidy-amqp
```

#### Tutorials

Create RabbitClient

```php
$client = new RabbitClient([
    'hostname' => '127.0.0.1',
    'port' => 5672,
    'username' => 'guest',
    'password' => 'guest'
]);
```

Create Channel

```php
$client->channel(function () use ($client) {
    // operation...
});
```

Create Queue Operate

```php
$client->channel(function () use ($client) {
    $client->queue('hello')->create();
});
```

Create Message

```php
$message = $client->message('one');
```