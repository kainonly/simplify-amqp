<?php
declare(strict_types=1);

namespace Simplify\AMQP\Factory;

use PhpAmqpLib\Message\AMQPMessage;

/**
 * Class Queue
 * @package Simplify\AMQP\Factory
 */
class QueueFactory extends BaseFactory
{
    /**
     * Declare Queue
     * @param array $options
     */
    public function create(array $options = []): void
    {
        $this->channel->queue_declare(
            $this->name,
            $options['passive'] ?? false,
            $options['durable'] ?? true,
            $options['exclusive'] ?? false,
            $options['auto_delete'] ?? false,
            false,
            $options['arguments'] ?? []
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
            $arguments
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
            $arguments
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
