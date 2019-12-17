<?php
declare(strict_types=1);

namespace tests;

use Exception;
use simplify\amqp\AMQPManager;

class ExchangeTest extends Base
{
    private $exchangeName;
    private $exchangeOtherName;
    private $queueName;

    protected function setUp(): void
    {
        parent::setUp();
        $this->exchangeName = 'exchange1-' . md5((string)time());
        $this->exchangeOtherName = 'exchange2-' . md5((string)time());
        $this->queueName = 'queue-' . md5((string)time());
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

    public function testCreateOtherExchange()
    {
        try {
            $this->client->channel(function (AMQPManager $manager) {
                $manager->exchange($this->exchangeOtherName)
                    ->setDeclare('direct', [
                        'durable' => true
                    ]);
                $this->assertNull(null);
            });
        } catch (Exception $e) {
            $this->expectErrorMessage($e->getMessage());
        }
    }

//    public function testCreateQueue()
//    {
//        try {
//            $this->client->channel(function (AMQPManager $manager) {
//                $manager->queue($this->queueName)
//                    ->setDeclare([
//                        'durable' => true
//                    ]);
//                $this->assertNull(null);
//            });
//        } catch (Exception $e) {
//            $this->expectErrorMessage($e->getMessage());
//        }
//    }

//    public function testBindExchange()
//    {
//        try {
//            $this->client->channel(function (AMQPManager $manager) {
//                $manager->exchange($this->exchangeName)
//                    ->bind($this->exchangeOtherName, [
//                        'routing_key' => 'simpliy'
//                    ]);
//                $this->assertNull(null);
//            });
//        } catch (Exception $e) {
//            $this->expectErrorMessage($e->getMessage());
//        }
//    }
//
//    public function testBindQueue()
//    {
//        try {
//            $this->client->channel(function (AMQPManager $manager) {
//                $manager->queue($this->queueName)->bind($this->exchangeName);
//                $this->assertNull(null);
//            });
//        } catch (Exception $e) {
//            $this->expectErrorMessage($e->getMessage());
//        }
//    }
//
//    public function testPublishMessage()
//    {
//        try {
//            $this->client->channel(function (AMQPManager $manager) {
//                $manager->publish(
//                    AMQPManager::message(
//                        json_encode([
//                            "name" => "kain"
//                        ])
//                    ),
//                    $this->exchangeOtherName,
//                    'simpliy'
//                );
//                $this->assertNull(null);
//            });
//        } catch (Exception $e) {
//            $this->expectErrorMessage($e->getMessage());
//        }
//    }
//
//    public function testGetQueueBindMessage()
//    {
//        try {
//            $this->client->channel(function (AMQPManager $manager) {
//                $message = $manager->queue($this->queueName)
//                    ->get();
//                $this->assertNotEmpty($message);
//                $data = json_decode($message->getBody());
//                $this->assertEquals($data->name, 'kain');
//                $manager->ack($message->getDeliveryTag());
//            });
//        } catch (Exception $e) {
//            $this->expectErrorMessage($e->getMessage());
//        }
//    }
//
//    public function testUnbindQueue()
//    {
//        try {
//            $this->client->channel(function (AMQPManager $manager) {
//                $manager->queue($this->queueName)->unbind($this->exchangeName);
//                $this->assertNull(null);
//            });
//        } catch (Exception $e) {
//            $this->expectErrorMessage($e->getMessage());
//        }
//    }
//
//    public function testUnbindExchange()
//    {
//        try {
//            $this->client->channel(function (AMQPManager $manager) {
//                $manager->exchange($this->exchangeName)
//                    ->unbind($this->exchangeOtherName, [
//                        'routing_key' => 'simpliy'
//                    ]);
//                $this->assertNull(null);
//            });
//        } catch (Exception $e) {
//            $this->expectErrorMessage($e->getMessage());
//        }
//    }

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

    public function testDeleteOtherExchange()
    {
        try {
            $this->client->channel(function (AMQPManager $manager) {
                $manager->exchange($this->exchangeOtherName)
                    ->delete();
                $this->assertNull(null);
            });
        } catch (Exception $e) {
            $this->expectErrorMessage($e->getMessage());
        }
    }

//    public function testDeleteQueue()
//    {
//        try {
//            $this->client->channel(function (AMQPManager $manager) {
//                $manager->queue($this->queueName)
//                    ->delete();
//                $this->assertNull(null);
//            });
//        } catch (Exception $e) {
//            $this->expectErrorMessage($e->getMessage());
//        }
//    }
}