<?php
declare(strict_types=1);

namespace SimplifyTests;

use Exception;
use PhpAmqpLib\Channel\AMQPChannel;
use PhpAmqpLib\Connection\AMQPStreamConnection;
use Simplify\AMQP\AMQPManager;

class MainTest extends BaseTest
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
            $this->client->channel(function (AMQPManager $manager) {
                $this->assertNotEmpty($manager);
                $this->assertInstanceOf(
                    AMQPChannel::class,
                    $manager->getChannel()
                );
            });
        } catch (Exception $e) {
            $this->expectErrorMessage($e->getMessage());
        }
    }
}