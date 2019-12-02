<?php
declare(strict_types=1);

namespace tests;

use PhpAmqpLib\Channel\AMQPChannel;
use PHPUnit\Framework\TestCase;
use tidy\amqp\Client;

class Exchange extends BaseTest
{
    public function testCreateExchange()
    {
        try {
            $this->client->channel(function () {
                $this->client
                    ->exchange('tidy')
                    ->setDeclare('direct', [
                        'durable' => true,
                        'nowait' => true
                    ]);
            });
        } catch (\Exception $e) {
            $this->expectDeprecationMessage($e->getMessage());
        }
    }

    /**
     * @depends testCreateExchange
     */
    public function testSendMessage()
    {
        try {
            $this->client->channel(function () {
                $message = $this->client->message(json_encode([
                    'version' => 'tidy'
                ]));
                $this->client->publish(
                    $message,
                    'tidy',
                    ''
                );
            });
        } catch (\Exception $e) {
            $this->expectErrorMessage($e->getMessage());
        }
    }

}