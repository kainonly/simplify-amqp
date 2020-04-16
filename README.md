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
use Simplify\AMQP\AMQPClient;
use Simplify\AMQP\AMQPManager;

$client = new AMQPClient(
    'localhost',
    5672,
    'guest',
    'guest',
    '/',
    [
        'insist' => false,
        'login_method' => 'AMQPLAIN',
        'login_response' => null,
        'locale' => 'zh_CN',
        'connection_timeout' => 5.0,
        'read_write_timeout' => 5.0,
        'context' => null,
        'keepalive' => true,
        'heartbeat' => 3.0,
        'channel_rpc_timeout' => 5.0,
        'ssl_protocol' => null
    ]
);

$client->channel(function (AMQPManager $manager) {
    // operate...
});
```

Create exchange and queue, and then bind them together

```php
use Simplify\AMQP\AMQPClient;
use Simplify\AMQP\AMQPManager;
use Simplify\AMQP\Common\ExchangeCreateOption;
use Simplify\AMQP\Common\ExchangeType;
use Simplify\AMQP\Common\QueueCreateOption;

$client = new AMQPClient('localhost',5672,'guest','guest');

$client->channel(function (AMQPManager $manager) {
    $exchangeOption = new ExchangeCreateOption();
    $exchangeOption->setType(ExchangeType::DIRECT());    
    $exchangeOption->setDurable(true);
    $manager->exchange('myexchange')->create($exchangeOption);
    
    $queueOption = new QueueCreateOption();
    $queueOption->setDurable(true);
    $queueOption->setMaxLength(3000);
    $queueOption->setMaxLengthBytes(1024*64);
    $queue = $manager->queue('myqueue');
    $queue->create($queueOption);
    $queue->bind('myexchange','');
});
```

> For more examples, please see unit testing