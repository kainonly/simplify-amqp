<?php
declare(strict_types=1);

namespace testing;

use PhpAmqpLib\Channel\AMQPChannel;
use PHPUnit\Framework\TestCase;
use tidy\amqp\Client;

class ExchangeTest extends TestCase
{
    public function testCreateExchange()
    {
        try {
            $client = new Client(
                getenv('hostname'),
                (int)getenv('port'),
                getenv('username'),
                getenv('password')
            );
            $client->channel(function () use ($client) {
                $result = $client
                    ->exchange('tidy')
                    ->create('direct');
                $this->assertNull($result, 'Create Exchange Failed');
            });
        } catch (\Exception $e) {
            $this->expectErrorMessage($e->getMessage());
        }
    }
}