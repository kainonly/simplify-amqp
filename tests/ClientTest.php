<?php
declare(strict_types=1);

namespace tests;

use Exception;
use PhpAmqpLib\Channel\AMQPChannel;
use PhpAmqpLib\Connection\AMQPStreamConnection;
use PhpAmqpLib\Message\AMQPMessage;

class ClientTest extends Base
{
    public function testGetConnection()
    {
        $this->assertInstanceOf(
            AMQPStreamConnection::class,
            $this->client->getConnection()
        );
    }

    public function testCreateChannel()
    {
        try {
            $this->client->channel(function (AMQPChannel $channel) {
                $this->assertNotEmpty($channel);
                $this->assertInstanceOf(
                    AMQPChannel::class,
                    $channel
                );
            });
        } catch (Exception $e) {
            $this->expectErrorMessage($e->getMessage());
        }
    }

    public function testCreateMessage()
    {
        $this->assertInstanceOf(
            AMQPMessage::class,
            $this->client->message(
                json_encode([
                    'name' => 'kain'
                ])
            )
        );
    }
}