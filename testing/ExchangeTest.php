<?php
declare(strict_types=1);

namespace testing;

use PhpAmqpLib\Channel\AMQPChannel;
use PHPUnit\Framework\TestCase;
use tidy\amqp\Client;

class ExchangeTest extends TestCase
{
    /**
     * @var Client
     */
    private $client;

    public function testCreateExchange()
    {
        try {
            $this->client = new Client(
                getenv('hostname'),
                (int)getenv('port'),
                getenv('username'),
                getenv('password')
            );
            $this->client->channel(function () {
                $result = $this->client
                    ->exchange('tidy')
                    ->create('direct');
                $this->assertNull($result, 'Create Exchange Failed');
            });
        } catch (\Exception $e) {
            $this->expectErrorMessage($e->getMessage());
        }
    }

    /**
     * @depends testCreateExchange
     */
    public function testSendMessage()
    {
        try {
            $this->client->channel(function () {
                $message = $this->client->message(json_encode([
                    'version' => 'tidy'
                ]));
                $this->client->publish(
                    $message,
                    'tidy',
                    ''
                );
            });
        } catch (\Exception $e) {
            $this->expectErrorMessage($e->getMessage());
        }
    }

}