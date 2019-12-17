<?php
declare(strict_types=1);

namespace tests;

use Exception;
use PhpAmqpLib\Channel\AMQPChannel;
use simplify\amqp\AMQPManager;

class QueueTest extends Base
{
    private $exchangeName;
    private $queueName;

    protected function setUp(): void
    {
        parent::setUp();
        $this->exchangeName = 'exchange-' . md5((string)time());
        $this->queueName = 'queue-' . md5((string)time());
    }

    public function testCreateQueue()
    {
        try {
            $this->client->channel(function (AMQPManager $manager) {
                $manager->queue($this->queueName)
                    ->setDeclare([
                        'durable' => true
                    ]);
                $this->assertNull(null);
            });
        } catch (Exception $e) {
            $this->expectErrorMessage($e->getMessage());
        }
    }

    public function testPublishMessage()
    {
        try {
            $this->client->channel(function (AMQPManager $manager) {
                $manager->publish(
                    AMQPManager::message(
                        json_encode([
                            "name" => "kain"
                        ])
                    ),
                    '',
                    $this->queueName
                );
                $this->assertNull(null);
            });
        } catch (Exception $e) {
            $this->expectErrorMessage($e->getMessage());
        }
    }

    public function testGetQueueMessage()
    {
        try {
            $this->client->channel(function (AMQPManager $manager) {
                $message = $manager->queue($this->queueName)
                    ->get();
                $this->assertNotEmpty($message);
                $data = json_decode($message->getBody());
                $this->assertEquals($data->name, 'kain');
                $manager->ack($message->getDeliveryTag());
            });
        } catch (Exception $e) {
            $this->expectErrorMessage($e->getMessage());
        }
    }

    public function testCreateExchange()
    {
        try {
            $this->client->channel(function (AMQPManager $manager) {
                $manager->exchange($this->exchangeName)
                    ->setDeclare('direct', [
                        'durable' => true
                    ]);
                $this->assertNull(null);
            });
        } catch (Exception $e) {
            $this->expectErrorMessage($e->getMessage());
        }
    }

    public function testBindQueue()
    {
        try {
            $this->client->channel(function (AMQPManager $manager) {
                $manager->queue($this->queueName)->bind($this->exchangeName, [
                    'routing_key' => 'simpliy'
                ]);
                $this->assertNull(null);
            });
        } catch (Exception $e) {
            $this->expectErrorMessage($e->getMessage());
        }
    }

    public function testPublishBindMessage()
    {
        try {
            $this->client->channel(function (AMQPManager $manager) {
                $manager->publish(
                    AMQPManager::message(
                        json_encode([
                            "type" => "bind"
                        ])
                    ),
                    $this->exchangeName,
                    'simpliy'
                );
                $this->assertNull(null);
            });
        } catch (Exception $e) {
            $this->expectErrorMessage($e->getMessage());
        }
    }

    public function testGetQueueBindMessage()
    {
        try {
            $this->client->channel(function (AMQPManager $manager) {
                $message = $manager->queue($this->queueName)
                    ->get();
                $this->assertNotEmpty($message);
                $data = json_decode($message->getBody());
                $this->assertEquals($data->type, 'bind');
                $manager->ack($message->getDeliveryTag());
            });
        } catch (Exception $e) {
            $this->expectErrorMessage($e->getMessage());
        }
    }

    public function testUnbindQueue()
    {
        try {
            $this->client->channel(function (AMQPManager $manager) {
                $manager->queue($this->queueName)->unbind($this->exchangeName, [
                    'routing_key' => 'simpliy'
                ]);
                $this->assertNull(null);
            });
        } catch (Exception $e) {
            $this->expectErrorMessage($e->getMessage());
        }
    }

    public function testPurgeQueue()
    {
        try {
            $this->client->channel(function (AMQPManager $manager) {
                $manager->queue($this->queueName)
                    ->purge();
                $this->assertNull(null);
            });
        } catch (Exception $e) {
            $this->expectErrorMessage($e->getMessage());
        }
    }

    public function testDeleteExchange()
    {
        try {
            $this->client->channel(function (AMQPManager $manager) {
                $manager->exchange($this->exchangeName)
                    ->delete();
                $this->assertNull(null);
            });
        } catch (Exception $e) {
            $this->expectErrorMessage($e->getMessage());
        }
    }

    public function testDeleteQueue()
    {
        try {
            $this->client->channel(function (AMQPManager $manager) {
                $manager->queue($this->queueName)
                    ->delete();
                $this->assertNull(null);
            });
        } catch (Exception $e) {
            $this->expectErrorMessage($e->getMessage());
        }
    }
}