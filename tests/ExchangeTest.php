<?php
declare(strict_types=1);

namespace SimplifyTests;

use Exception;
use Simplify\AMQP\AMQPManager;

class ExchangeTest extends BaseTest
{
    private string $exchangeName;
    private string $exchangeOtherName;
    private string $queueName;

    public function __construct($name = null, array $data = [], $dataName = '')
    {
        parent::__construct($name, $data, $dataName);
        $this->exchangeName = 'exchange1-' . md5('exchange');
        $this->exchangeOtherName = 'exchange2-' . md5('exchange');
        $this->queueName = 'queue-' . md5('exchange');
    }

    public function testCreateExchange()
    {
        try {
            $this->client->channel(function (AMQPManager $manager) {
                $manager->exchange($this->exchangeName)
                    ->create('direct', [
                        'durable' => true
                    ]);
                $this->assertNull(null);
            });
        } catch (Exception $e) {
            $this->expectErrorMessage($e->getMessage());
        }
    }

    public function testCreateOtherExchange()
    {
        try {
            $this->client->channel(function (AMQPManager $manager) {
                $manager->exchange($this->exchangeOtherName)
                    ->create('direct', [
                        'durable' => true
                    ]);
                $this->assertNull(null);
            });
        } catch (Exception $e) {
            $this->expectErrorMessage($e->getMessage());
        }
    }

    public function testBindExchange()
    {
        try {
            $this->client->channel(function (AMQPManager $manager) {
                $manager->exchange($this->exchangeName)
                    ->bind($this->exchangeOtherName, [
                        'routing_key' => 'simpliy'
                    ]);
                $this->assertNull(null);
            });
        } catch (Exception $e) {
            $this->expectErrorMessage($e->getMessage());
        }
    }

    public function testUnbindExchange()
    {
        try {
            $this->client->channel(function (AMQPManager $manager) {
                $manager->exchange($this->exchangeName)
                    ->unbind($this->exchangeOtherName, [
                        'routing_key' => 'simpliy'
                    ]);
                $this->assertNull(null);
            });
        } catch (Exception $e) {
            $this->expectErrorMessage($e->getMessage());
        }
    }

    public function testDeleteExchange()
    {
        try {
            $this->client->channel(function (AMQPManager $manager) {
                $manager->exchange($this->exchangeName)
                    ->delete();
                $this->assertNull(null);
            });
        } catch (Exception $e) {
            $this->expectErrorMessage($e->getMessage());
        }
    }

    public function testDeleteOtherExchange()
    {
        try {
            $this->client->channel(function (AMQPManager $manager) {
                $manager->exchange($this->exchangeOtherName)
                    ->delete();
                $this->assertNull(null);
            });
        } catch (Exception $e) {
            $this->expectErrorMessage($e->getMessage());
        }
    }
}