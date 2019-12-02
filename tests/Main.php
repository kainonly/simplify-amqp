<?php
declare(strict_types=1);

namespace tests;

use PhpAmqpLib\Channel\AMQPChannel;
use PhpAmqpLib\Connection\AMQPStreamConnection;

class Main extends BaseTest
{
    public function testConnection()
    {
        $this->assertInstanceOf(
            AMQPStreamConnection::class,
            $this->client->getConnection()
        );
    }

    public function testChannel()
    {
        try {
            $this->client->channel(function () {
                $this->assertInstanceOf(
                    AMQPChannel::class,
                    $this->client->getChannel()
                );
            });
        } catch (\Exception $e) {

        }
    }
}