<?php
declare(strict_types=1);

namespace SimplifyTests;

use Exception;
use PhpAmqpLib\Message\AMQPMessage;
use Simplify\AMQP\AMQPManager;
use Simplify\AMQP\Common\ExchangeCreateOption;
use Simplify\AMQP\Common\ExchangeType;
use Simplify\AMQP\Common\QueueCreateOption;

class QueueTest extends BaseTest
{
    private string $exchangeName = 'simplify-exchange-001';
    private string $queueName = 'simplify-queue-001';

    public function testCreateQueue(): void
    {
        try {
            $this->client->channel(function (AMQPManager $manager) {
                $option = new QueueCreateOption();
                $option->setDurable(false);
                $manager->queue($this->queueName)
                    ->create($option);
                $this->assertTrue(true);
            });
        } catch (Exception $e) {
            $this->expectErrorMessage($e->getMessage());
        }
    }

    public function testPublishMessage(): void
    {
        try {
            $this->client->channel(function (AMQPManager $manager) {
                $message = new AMQPMessage(json_encode([
                    "name" => "kain"
                ]));
                $manager->publish($message, '', $this->queueName);
                $this->assertTrue(true);
            });
        } catch (Exception $e) {
            $this->expectErrorMessage($e->getMessage());
        }
    }

    public function testGetQueueMessage(): void
    {
        try {
            $this->client->channel(function (AMQPManager $manager) {
                $message = $manager->queue($this->queueName)->get();
                $this->assertNotEmpty($message);
                $data = json_decode($message->getBody());
                $this->assertSame($data->name, 'kain');
                $manager->ack($message->getDeliveryTag());
            });
        } catch (Exception $e) {
            $this->expectErrorMessage($e->getMessage());
        }
    }

    public function testCreateExchange(): void
    {
        try {
            $this->client->channel(function (AMQPManager $manager) {
                $option = new ExchangeCreateOption();
                $option->setType(ExchangeType::DIRECT());
                $option->setDurable(false);
                $manager->exchange($this->exchangeName)
                    ->create($option);
                $this->assertTrue(true);
            });
        } catch (Exception $e) {
            $this->expectErrorMessage($e->getMessage());
        }
    }

    public function testBindQueue(): void
    {
        try {
            $this->client->channel(function (AMQPManager $manager) {
                $manager
                    ->queue($this->queueName)
                    ->bind($this->exchangeName, 'simpliy');
                $this->assertTrue(true);
            });
        } catch (Exception $e) {
            $this->expectErrorMessage($e->getMessage());
        }
    }

    public function testPublishBindMessage(): void
    {
        try {
            $this->client->channel(function (AMQPManager $manager) {
                $message = new AMQPMessage(json_encode([
                    "type" => "bind"
                ]));
                $manager->publish($message, $this->exchangeName, 'simpliy');
                $this->assertTrue(true);
            });
        } catch (Exception $e) {
            $this->expectErrorMessage($e->getMessage());
        }
    }

    public function testGetQueueBindMessage(): void
    {
        try {
            $this->client->channel(function (AMQPManager $manager) {
                $message = $manager->queue($this->queueName)->get();
                $this->assertNotEmpty($message);
                $data = json_decode($message->getBody());
                $this->assertSame($data->type, 'bind');
                $manager->ack($message->getDeliveryTag());
            });
        } catch (Exception $e) {
            $this->expectErrorMessage($e->getMessage());
        }
    }

    public function testUnbindQueue(): void
    {
        try {
            $this->client->channel(function (AMQPManager $manager) {
                $manager
                    ->queue($this->queueName)
                    ->unbind($this->exchangeName, 'simpliy');
                $this->assertTrue(true);
            });
        } catch (Exception $e) {
            $this->expectErrorMessage($e->getMessage());
        }
    }

    public function testPurgeQueue(): void
    {
        try {
            $this->client->channel(function (AMQPManager $manager) {
                $manager->queue($this->queueName)->purge();
                $this->assertTrue(true);
            });
        } catch (Exception $e) {
            $this->expectErrorMessage($e->getMessage());
        }
    }

    public function testDeleteExchange(): void
    {
        try {
            $this->client->channel(function (AMQPManager $manager) {
                $manager->exchange($this->exchangeName)->delete();
                $this->assertTrue(true);
            });
        } catch (Exception $e) {
            $this->expectErrorMessage($e->getMessage());
        }
    }

    public function testDeleteQueue(): void
    {
        try {
            $this->client->channel(function (AMQPManager $manager) {
                $manager->queue($this->queueName)->delete();
                $this->assertTrue(true);
            });
        } catch (Exception $e) {
            $this->expectErrorMessage($e->getMessage());
        }
    }
}