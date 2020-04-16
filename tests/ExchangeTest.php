<?php
declare(strict_types=1);

namespace SimplifyTests;

use Exception;
use Simplify\AMQP\AMQPManager;
use Simplify\AMQP\Common\ExchangeCreateOption;
use Simplify\AMQP\Common\ExchangeType;

class ExchangeTest extends BaseTest
{
    private string $exchangeName = 'simplify-exchange-011';
    private string $exchangeOtherName = 'simplify-exchange-012';

    public function testCreateExchange(): void
    {
        try {
            $this->client->channel(function (AMQPManager $manager) {
                $option = new ExchangeCreateOption();
                $option->setType(ExchangeType::DIRECT());
                $option->setDurable(false);
                $manager->exchange($this->exchangeName)
                    ->create($option);
                $this->assertTrue(true);
            });
        } catch (Exception $e) {
            $this->expectErrorMessage($e->getMessage());
        }
    }

    public function testCreateOtherExchange(): void
    {
        try {
            $this->client->channel(function (AMQPManager $manager) {
                $option = new ExchangeCreateOption();
                $option->setType(ExchangeType::TOPIC());
                $option->setDurable(true);
                $manager->exchange($this->exchangeOtherName)
                    ->create($option);
                $this->assertTrue(true);
            });
        } catch (Exception $e) {
            $this->expectErrorMessage($e->getMessage());
        }
    }

    public function testBindExchange(): void
    {
        try {
            $this->client->channel(function (AMQPManager $manager) {
                $manager->exchange($this->exchangeName)
                    ->bind($this->exchangeOtherName, 'simpliy');
                $this->assertTrue(true);
            });
        } catch (Exception $e) {
            $this->expectErrorMessage($e->getMessage());
        }
    }

    public function testUnbindExchange(): void
    {
        try {
            $this->client->channel(function (AMQPManager $manager) {
                $manager->exchange($this->exchangeName)
                    ->unbind($this->exchangeOtherName, 'simpliy');
                $this->assertTrue(true);
            });
        } catch (Exception $e) {
            $this->expectErrorMessage($e->getMessage());
        }
    }

    public function testDeleteExchange(): void
    {
        try {
            $this->client->channel(function (AMQPManager $manager) {
                $manager->exchange($this->exchangeName)->delete();
                $this->assertTrue(true);
            });
        } catch (Exception $e) {
            $this->expectErrorMessage($e->getMessage());
        }
    }

    public function testDeleteOtherExchange(): void
    {
        try {
            $this->client->channel(function (AMQPManager $manager) {
                $manager->exchange($this->exchangeOtherName)->delete();
                $this->assertTrue(true);
            });
        } catch (Exception $e) {
            $this->expectErrorMessage($e->getMessage());
        }
    }
}