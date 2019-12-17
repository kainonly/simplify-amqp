<?php
declare(strict_types=1);

namespace tests;

use Exception;
use simplify\amqp\AMQPManager;

class FeedbackTest extends Base
{
    private $queueName;

    protected function setUp(): void
    {
        parent::setUp();
        $this->queueName = 'queue-feedback';
    }

    public function testCreateQueue()
    {
        try {
            $this->client->channel(function (AMQPManager $manager) {
                $manager->queue($this->queueName)
                    ->setDeclare([
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
                    AMQPManager::message('Test'),
                    '',
                    $this->queueName
                );
                sleep(1);
                $message = $manager->queue($this->queueName)->get();
                $this->assertNotEmpty($message);
                $this->assertEquals($message->getBody(), 'Test');
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
                    AMQPManager::message('Test'),
                    '',
                    $this->queueName
                );
                sleep(1);
                $message = $manager->queue($this->queueName)->get();
                $this->assertNotEmpty($message);
                $this->assertEquals($message->getBody(), 'Test');
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