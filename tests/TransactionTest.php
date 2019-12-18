<?php
declare(strict_types=1);

namespace tests;

use Exception;
use simplify\amqp\AMQPManager;

class TransactionTest extends Base
{
    private $queueName;

    protected function setUp(): void
    {
        parent::setUp();
        $this->queueName = 'queue-' . md5('transaction');
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

    public function testPublishMessageSuccess()
    {
        try {
            $this->client->channeltx(function (AMQPManager $manager) {
                $manager->publish(
                    AMQPManager::message(
                        json_encode([
                            "name" => "kain"
                        ])
                    ),
                    '',
                    $this->queueName
                );
                return true;
            });
            $this->assertNull(null);
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

    public function testPublishMessageFailed()
    {
        try {
            $this->client->channeltx(function (AMQPManager $manager) {
                $manager->publish(
                    AMQPManager::message(
                        json_encode([
                            "name" => "kain"
                        ])
                    ),
                    '',
                    $this->queueName
                );
                return false;
            });
            $this->assertNull(null);
        } catch (Exception $e) {
            $this->expectErrorMessage($e->getMessage());
        }
    }

    public function testGetQueueMessageEmpty()
    {
        try {
            $this->client->channel(function (AMQPManager $manager) {
                $message = $manager->queue($this->queueName)
                    ->get();
                $this->assertEmpty($message);
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