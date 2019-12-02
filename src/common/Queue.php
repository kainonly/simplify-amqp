<?php
declare(strict_types=1);

namespace simplify\amqp\common;

/**
 * Class Queue
 * @package tidy\amqp\common
 */
final class Queue extends Type
{
    /**
     * Declare Queue
     * @param array $options
     */
    public function setDeclare(array $options = []): void
    {
        $options = array_merge([
            'passive' => false,
            'durable' => false,
            'exclusive' => false,
            'auto_delete' => true,
            'nowait' => false,
            'arguments' => [],
            'ticket' => null
        ], $options);

        $this->channel->queue_declare(
            $this->name,
            $options['passive'],
            $options['durable'],
            $options['exclusive'],
            $options['auto_delete'],
            $options['nowait'],
            $options['arguments'],
            $options['ticket']
        );
    }

    /**
     * Bind Exchange
     * @param string $exchangeName exchange name
     * @param array $options
     */
    public function bind(string $exchangeName, array $options = []): void
    {
        $options = array_merge([
            'routing_key' => '',
            'nowait' => false,
            'arguments' => [],
            'ticket' => null
        ], $options);

        $this->channel->queue_bind(
            $this->name,
            $exchangeName,
            $options['routing_key'],
            $options['nowait'],
            $options['arguments'],
            $options['ticket']
        );
    }

    /**
     * Unbind Exchange
     * @param string $exchangeName exchange name
     * @param array $options
     */
    public function unbind(string $exchangeName, array $options = []): void
    {
        $options = array_merge([
            'routing_key' => '',
            'arguments' => [],
            'ticket' => null
        ], $options);

        $this->channel->queue_unbind(
            $this->name,
            $exchangeName,
            $options['routing_key'],
            $options['arguments'],
            $options['ticket']
        );
    }

    /**
     * Purge Queue
     * @param array $options
     */
    public function purge(array $options = []): void
    {
        $options = array_merge([
            'nowait' => false,
            'ticket' => null
        ], $options);

        $this->channel->queue_purge(
            $this->name,
            $options['nowait'],
            $options['ticket']
        );
    }

    /**
     * Delete Queue
     * @param array $options
     */
    public function delete(array $options = []): void
    {
        $options = array_merge([
            'if_unused' => false,
            'if_empty' => false,
            'nowait' => false,
            'ticket' => null
        ], $options);

        $this->channel->queue_delete(
            $this->name,
            $options['if_unused'],
            $options['if_empty'],
            $options['nowait'],
            $options['ticket']
        );
    }

    /**
     * Get Queue
     * @param array $options
     */
    public function get(array $options = []): void
    {
        $options = array_merge([
            'no_ack' => false,
            'ticket' => null
        ], $options);

        return $this->channel->basic_get(
            $this->name,
            $options['no_ack'],
            $options['ticket']
        );
    }
}
