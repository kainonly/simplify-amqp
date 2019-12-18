<?php
declare(strict_types=1);

namespace tests;

use Exception;
use PhpAmqpLib\Message\AMQPMessage;
use simplify\amqp\AMQPManager;

class ConsumerTest extends Base
{
    private $queueName;
    private $consumerName;

    protected function setUp(): void
    {
        parent::setUp();
        $this->queueName = 'queue-' . md5('consumer');
        $this->consumerName = 'consumer-' . md5('consumer');
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

    public function testSubscribe()
    {
        try {
            $this->client->channel(function (AMQPManager $manager) {
                $always = true;
                $manager->consumer($this->consumerName)
                    ->subscribe($this->queueName, function (AMQPMessage $msg) use ($manager, &$always) {
                        $this->assertNotEmpty($msg);
                        $data = json_decode($msg->getBody());
                        $this->assertEquals($data->name, 'kain');
                        $manager->ack($msg->getDeliveryTag());
                        $always = false;
                    });
                $channel = $manager->getChannel();
                while ($channel->is_consuming() && $always) {
                    $channel->wait();
                    $this->assertNull(null);
                }
            });
        } catch (Exception $e) {
            $this->expectErrorMessage($e->getMessage());
        }
    }

    public function testUnsubscribe()
    {
        try {
            $this->client->channel(function (AMQPManager $manager) {
                $manager->consumer($this->consumerName)
                    ->unsubscribe();
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