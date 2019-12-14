<?php
declare(strict_types=1);

namespace tests;

use Exception;
use PhpAmqpLib\Channel\AMQPChannel;

class QueueTest extends Base
{
    public function testCreateSampleQueue()
    {
        try {
            $this->client->channel(function (AMQPChannel $channel) {
                $this->client
                    ->queue('hello')
                    ->setDeclare([
                    ]);
            });
        } catch (Exception $e) {
            $this->expectErrorMessage($e->getMessage());
        }
    }
}