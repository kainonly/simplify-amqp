<?php
declare(strict_types=1);

namespace SimplifyTests;

use Exception;
use PhpAmqpLib\Message\AMQPMessage;
use Simplify\AMQP\AMQPManager;

class FeedbackTest extends BaseTest
{
    private string $queueName;

    public function __construct($name = null, array $data = [], $dataName = '')
    {
        parent::__construct($name, $data, $dataName);
        $this->queueName = 'queue-' . md5('feedback');
    }

    public function testCreateQueue()
    {
        try {
            $this->client->channel(function (AMQPManager $manager) {
                $manager->queue($this->queueName)
                    ->create([
                        'durable' => true,
                        'auto_delete' => false
                    ]);
                $this->assertNull(null);
            });
        } catch (Exception $e) {
            $this->expectErrorMessage($e->getMessage());
        }
    }

    public function testNack()
    {
        try {
            $this->client->channel(function (AMQPManager $manager) {
                $manager->publish(
                    new AMQPMessage('Test'),
                    '',
                    $this->queueName
                );
                sleep(1);
                $message = $manager->queue($this->queueName)->get();
                $this->assertNotEmpty($message);
                $this->assertSame($message->getBody(), 'Test');
                $manager->nack($message->getDeliveryTag());
            });
        } catch (Exception $e) {
            $this->expectErrorMessage($e->getMessage());
        }
    }

    public function testReject()
    {
        try {
            $this->client->channel(function (AMQPManager $manager) {
                $manager->publish(
                    new AMQPMessage('Test'),
                    '',
                    $this->queueName
                );
                sleep(1);
                $message = $manager->queue($this->queueName)->get();
                $this->assertNotEmpty($message);
                $this->assertSame($message->getBody(), 'Test');
                $manager->reject($message->getDeliveryTag());
            });
        } catch (Exception $e) {
            $this->expectErrorMessage($e->getMessage());
        }
    }

    public function testRevover()
    {
        try {
            $this->client->channel(function (AMQPManager $manager) {
                $manager->revover(true);
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