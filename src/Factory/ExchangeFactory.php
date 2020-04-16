<?php
declare(strict_types=1);

namespace Simplify\AMQP\Factory;

use PhpAmqpLib\Wire\AMQPTable;
use Simplify\AMQP\Common\ExchangeCreateOption;

/**
 * Class Exchange
 * @package Simplify\AMQP\Factory
 */
class ExchangeFactory extends BaseFactory
{
    /**
     * declare exchange
     * @param ExchangeCreateOption $option
     */
    public function create(ExchangeCreateOption $option): void
    {
        $this->channel->exchange_declare(
            $this->name,
            $option->getType()->getValue(),
            $option->isPassive(),
            $option->isDurable(),
            $option->isAutoDelete(),
            $option->isInternal(),
            false,
            new AMQPTable($option->getArguments())
        );
    }

    /**
     * bind exchange
     * @param string $destination dest exchange
     * @param string $routing_key
     * @param array $arguments
     */
    public function bind(string $destination, string $routing_key = '', array $arguments = []): void
    {
        $this->channel->exchange_bind(
            $destination,
            $this->name,
            $routing_key,
            false,
            new AMQPTable($arguments)
        );
    }

    /**
     * unbind exchange
     * @param string $destination dest exchange
     * @param string $routing_key
     * @param array $arguments
     */
    public function unbind(string $destination, string $routing_key = '', array $arguments = []): void
    {
        $this->channel->exchange_unbind(
            $destination,
            $this->name,
            $routing_key,
            false,
            new AMQPTable($arguments),
        );
    }

    /**
     * delete Exchange
     */
    public function delete(): void
    {
        $this->channel->exchange_delete($this->name);
    }
}
