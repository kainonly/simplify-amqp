<?php
declare(strict_types=1);

namespace tests;

use Exception;
use PhpAmqpLib\Channel\AMQPChannel;
use PhpAmqpLib\Connection\AMQPStreamConnection;
use PhpAmqpLib\Message\AMQPMessage;
use simplify\amqp\AMQPManager;

class MainTest extends Base
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

    public function testCreateMessage()
    {
        try {
            $this->assertInstanceOf(
                AMQPMessage::class,
                AMQPManager::message(json_encode([
                    'name' => 'kain'
                ]))
            );
        } catch (Exception $e) {
            $this->expectErrorMessage($e->getMessage());
        }
    }
}