## Simplify AMQP

Simplify amqp operation library php-amqplib / php-amqplib

[![Packagist Version](https://img.shields.io/packagist/v/kain/simplify-amqp.svg?style=flat-square)](https://packagist.org/packages/kain/simplify-amqp)
[![Travis (.org)](https://img.shields.io/travis/kainonly/simplify-amqp.svg?style=flat-square)](https://travis-ci.org/kainonly/simplify-amqp)
[![Coveralls github](https://img.shields.io/coveralls/github/kainonly/simplify-amqp.svg?style=flat-square)](https://coveralls.io/github/kainonly/simplify-amqp)
[![PHP from Packagist](https://img.shields.io/packagist/php-v/kain/simplify-amqp.svg?color=blue&style=flat-square)](https://github.com/kainonly/simplify-amqp)
[![Packagist](https://img.shields.io/packagist/dt/kain/simplify-amqp.svg?color=blue&style=flat-square)](https://packagist.org/packages/kain/simplify-amqp)
[![License](https://img.shields.io/packagist/l/kain/simplify-amqp.svg?color=blue&style=flat-square)](https://github.com/kainonly/simplify-amqp/blob/master/LICENSE)

#### Setup

```shell
composer require kain/simplify-amqp
```

#### Usage

Create an AMQP client

```php
$client->channel(function (AMQPManager $manager) {});
```

Declare a exchange

```php
$client->channel(function (AMQPManager $manager) {
    $manager->exchange('anyone')
        ->setDeclare('direct', [
            'durable' => true
        ]);
});
```