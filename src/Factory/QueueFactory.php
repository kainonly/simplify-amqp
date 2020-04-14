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
     * @param string $exchangeName exchange name
     * @param array $options
     */
    public function bind(string $exchangeName, array $options = []): void
    {
        $this->channel->queue_bind(
            $this->name,
            $exchangeName,
            $options['routing_key'] ?? '',
            false,
            $options['arguments'] ?? []
        );
    }

    /**
     * Unbind Exchange
     * @param string $exchangeName exchange name
     * @param array $options
     */
    public function unbind(string $exchangeName, array $options = []): void
    {
        $this->channel->queue_unbind(
            $this->name,
            $exchangeName,
            $options['routing_key'] ?? '',
            $options['arguments'] ?? []
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
     * @param array $options
     * @return AMQPMessage|null
     */
    public function get(array $options = []): ?AMQPMessage
    {
        return $this->channel->basic_get(
            $this->name,
            $options['no_ack'] ?? false
        );
    }
}
