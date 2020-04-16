<?php
declare(strict_types=1);

namespace Simplify\AMQP\Factory;

use PhpAmqpLib\Message\AMQPMessage;
use PhpAmqpLib\Wire\AMQPTable;
use Simplify\AMQP\Common\QueueCreateOption;

/**
 * Class Queue
 * @package Simplify\AMQP\Factory
 */
class QueueFactory extends BaseFactory
{
    /**
     * Declare Queue
     * @param QueueCreateOption $option
     */
    public function create(QueueCreateOption $option): void
    {
        $this->channel->queue_declare(
            $this->name,
            $option->isPassive(),
            $option->isDurable(),
            $option->isExclusive(),
            $option->isAutoDelete(),
            false,
            new AMQPTable($option->getArguments())
        );
    }

    /**
     * Bind Exchange
     * @param string $exchange
     * @param string $routing_key
     * @param array $arguments
     */
    public function bind(string $exchange, string $routing_key = '', array $arguments = []): void
    {
        $this->channel->queue_bind(
            $this->name,
            $exchange,
            $routing_key,
            false,
            new AMQPTable($arguments)
        );
    }

    /**
     * Unbind Exchange
     * @param string $exchange
     * @param string $routing_key
     * @param array $arguments
     */
    public function unbind(string $exchange, string $routing_key = '', array $arguments = []): void
    {
        $this->channel->queue_unbind(
            $this->name,
            $exchange,
            $routing_key,
            new AMQPTable($arguments)
        );
    }

    /**
     * Purge Queue
     */
    public function purge(): void
    {
        $this->channel->queue_purge($this->name);
    }

    /**
     * Delete Queue
     */
    public function delete(): void
    {
        $this->channel->queue_delete($this->name);
    }

    /**
     * Get Queue Message
     * @param bool $no_ack
     * @return AMQPMessage
     */
    public function get(bool $no_ack = false): AMQPMessage
    {
        return $this->channel->basic_get(
            $this->name,
            $no_ack
        );
    }
}
