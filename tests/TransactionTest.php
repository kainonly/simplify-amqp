<?php
declare(strict_types=1);

namespace SimplifyTests;

use Exception;
use PhpAmqpLib\Message\AMQPMessage;
use Simplify\AMQP\AMQPManager;

class TransactionTest extends BaseTest
{
    private string $queueName;

    public function __construct($name = null, array $data = [], $dataName = '')
    {
        parent::__construct($name, $data, $dataName);
        $this->queueName = 'queue-' . md5('transaction');
    }

    public function testCreateQueue()
    {
        try {
            $this->client->channel(function (AMQPManager $manager) {
                $manager->queue($this->queueName)
                    ->create([
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
                    new AMQPMessage(
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

    public function testPublishMessageFailed()
    {
        try {
            $this->client->channeltx(function (AMQPManager $manager) {
                $manager->publish(
                    new AMQPMessage(
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