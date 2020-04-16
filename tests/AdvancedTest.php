<?php
declare(strict_types=1);

namespace SimplifyTests;

use Exception;
use PhpAmqpLib\Message\AMQPMessage;
use Simplify\AMQP\AMQPManager;
use Simplify\AMQP\Common\ExchangeCreateOption;
use Simplify\AMQP\Common\ExchangeType;
use Simplify\AMQP\Common\QueueCreateOption;

class AdvancedTest extends BaseTest
{
    private string $exchangeName = 'simplify-exchange-031';
    private string $queueName = 'simplify-queue-031';
    private string $deadQueueName = 'simplify-queue-032';

    public function testCreateExchange(): void
    {
        try {
            $this->client->channel(function (AMQPManager $manager) {
                $option = new ExchangeCreateOption();
                $option->setType(ExchangeType::DIRECT());
                $option->setPassive(false);
                $option->setDurable(false);
                $option->setAutoDelete(false);
                $option->setInternal(false);
                $option->setAlternateExchange('');
                $option->appendArgument('foo', 'any');
                $manager->exchange($this->exchangeName)->create($option);
                $this->assertTrue(true);
            });
        } catch (Exception $e) {
            $this->expectErrorMessage($e->getMessage());
        }
    }

    public function testCreateDeadQueueBindExchange(): void
    {
        try {
            $this->client->channel(function (AMQPManager $manager) {
                $option = new QueueCreateOption();
                $option->setExclusive(false);
                $option->setAutoDelete(false);
                $option->setDurable(true);
                $option->setPassive(false);
                $option->setMessageTTL(1000 * 60 * 60 * 8);
                $option->setSingleActiveConsumer(true);
                $option->setOverflow([
                    'reject-publish'
                ]);
                $option->setMaxPriority(1);
                $option->setQueueMasterLocator('min-masters');
                $option->setQueueLazyMode();
                $option->appendArgument('foo', 'any');
                $queue = $manager->queue($this->deadQueueName);
                $queue->create($option);
                $queue->bind($this->exchangeName, 'dead');
                $this->assertTrue(true);
            });
        } catch (Exception $e) {
            $this->expectErrorMessage($e->getMessage());
        }
    }

    public function testCreateQueueBindExchange(): void
    {
        try {
            $this->client->channel(function (AMQPManager $manager) {
                $option = new QueueCreateOption();
                $option->setAutoDelete(false);
                $option->setDurable(false);
                $option->setMaxLength(50);
                $option->setMaxLengthBytes(1024 * 64);
                $option->setDeadLetterExchange($this->exchangeName);
                $option->setDeadLetterRoutingKey('dead');
                $queue = $manager->queue($this->queueName);
                $queue->create($option);
                $queue->bind($this->exchangeName, '');
                $this->assertTrue(true);
            });
        } catch (Exception $e) {
            $this->expectErrorMessage($e->getMessage());
        }
    }

    public function testPublishMessageCommit(): void
    {
        try {
            $this->client->channeltx(function (AMQPManager $manager) {
                $message = new AMQPMessage(
                    json_encode([
                        "status" => "success"
                    ])
                );
                $manager->publish($message, $this->exchangeName, '');
                return true;
            });
            $this->assertTrue(true);
        } catch (Exception $e) {
            $this->expectErrorMessage($e->getMessage());
        }
    }

    public function testPublishMessageRollback(): void
    {
        try {
            $this->client->channeltx(function (AMQPManager $manager) {
                $message = new AMQPMessage(
                    json_encode([
                        "status" => "failed"
                    ])
                );
                $manager->publish($message, $this->exchangeName, '');
                return false;
            });
            $this->assertTrue(true);
        } catch (Exception $e) {
            $this->expectErrorMessage($e->getMessage());
        }
    }

    public function testNack(): void
    {
        try {
            $this->client->channel(function (AMQPManager $manager) {
                $message = $manager->queue($this->queueName)->get();
                $this->assertNotEmpty($message);
                $data = json_decode($message->getBody(), true);
                $this->assertSame($data, [
                    "status" => "success"
                ]);
                $manager->nack($message->getDeliveryTag());
                $this->assertTrue(true);
            });
        } catch (Exception $e) {
            $this->expectErrorMessage($e->getMessage());
        }
    }

    public function testReject(): void
    {
        try {
            $this->client->channel(function (AMQPManager $manager) {
                $message = $manager->queue($this->deadQueueName)->get();
                $this->assertNotEmpty($message);
                $data = json_decode($message->getBody(), true);
                $this->assertSame($data, [
                    "status" => "success"
                ]);
                $manager->reject($message->getDeliveryTag());
                $this->assertTrue(true);
            });
        } catch (Exception $e) {
            $this->expectErrorMessage($e->getMessage());
        }
    }

    public function testRevover(): void
    {
        try {
            $this->client->channel(function (AMQPManager $manager) {
                $manager->revover(true);
                $this->assertTrue(true);
            });
        } catch (Exception $e) {
            $this->expectErrorMessage($e->getMessage());
        }
    }

    public function testDelete(): void
    {
        try {
            $this->client->channel(function (AMQPManager $manager) {
                $manager->queue($this->queueName)->delete();
                $manager->queue($this->deadQueueName)->delete();
                $manager->exchange($this->exchangeName)->delete();
                $this->assertTrue(true);
            });
        } catch (Exception $e) {
            $this->expectErrorMessage($e->getMessage());
        }
    }
}