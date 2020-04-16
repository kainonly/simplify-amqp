<?php
declare(strict_types=1);

namespace SimplifyTests;

use Exception;
use PhpAmqpLib\Message\AMQPMessage;
use Simplify\AMQP\AMQPManager;
use Simplify\AMQP\Common\QueueCreateOption;

class ConsumerTest extends BaseTest
{
    private string $queueName = 'simplify-queue-021';
    private string $consumerName = 'simplify-consumer-021';

    public function testCreateQueue(): void
    {
        try {
            $this->client->channel(function (AMQPManager $manager) {
                $option = new QueueCreateOption();
                $option->setDurable(true);
                $option->setAutoDelete(false);
                $option->setAutoExpire(1000 * 30);
                $option->setQueueLazyMode();
                $manager->queue($this->queueName)->create($option);
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
                $message = new AMQPMessage(
                    json_encode([
                        "name" => "kain"
                    ])
                );
                $manager->publish($message, '', $this->queueName);
                $this->assertTrue(true);
            });
        } catch (Exception $e) {
            $this->expectErrorMessage($e->getMessage());
        }
    }

    public function testSubscribe(): void
    {
        try {
            $this->client->channel(function (AMQPManager $manager) {
                $always = true;
                $manager->consumer($this->consumerName)
                    ->subscribe($this->queueName, function (AMQPMessage $msg) use ($manager, &$always) {
                        $this->assertNotEmpty($msg);
                        $data = json_decode($msg->getBody());
                        $this->assertSame($data->name, 'kain');
                        $manager->ack($msg->getDeliveryTag());
                        $always = false;
                    });
                $channel = $manager->getChannel();
                while ($channel->is_consuming() && $always) {
                    $channel->wait();
                    $this->assertTrue(true);
                }
            });
        } catch (Exception $e) {
            $this->expectErrorMessage($e->getMessage());
        }
    }

    public function testUnsubscribe(): void
    {
        try {
            $this->client->channel(function (AMQPManager $manager) {
                $manager->consumer($this->consumerName)->unsubscribe();
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