<?php
declare(strict_types=1);

namespace SimplifyTests;

use Exception;
use PhpAmqpLib\Message\AMQPMessage;
use Simplify\AMQP\AMQPManager;
use Simplify\AMQP\Common\QueueCreateOption;

class ConsumerTest extends BaseTest
{
    private string $queueName;
    private string $consumerName;

    public function __construct($name = null, array $data = [], $dataName = '')
    {
        parent::__construct($name, $data, $dataName);
        $this->queueName = 'queue-' . md5('consumer');
        $this->consumerName = 'consumer-' . md5('consumer');
    }

    public function testCreateQueue()
    {
        try {
            $this->client->channel(function (AMQPManager $manager) {
                $option = new QueueCreateOption();
                $option->setDurable(true);
                $option->setAutoDelete(false);
                $option->setAutoExpire(1000 * 30);
                $option->setQueueLazyMode();
                $manager->queue($this->queueName)
                    ->create($option);
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
                    new AMQPMessage(
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
                        $this->assertSame($data->name, 'kain');
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