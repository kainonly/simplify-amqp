<?php
declare(strict_types=1);

namespace tests;

use Exception;
use PhpAmqpLib\Channel\AMQPChannel;
use simplify\amqp\AMQPManager;

class QueueTest extends Base
{
    public function testCreateSampleQueue()
    {
        try {
            $this->client->channel(function (AMQPManager $manager) {
                $manager->queue('hello')->setDeclare();
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
                $manager->queue('hello')->delete();
                $this->assertNull(null);
            });
        } catch (Exception $e) {
            $this->expectErrorMessage($e->getMessage());
        }
    }
}