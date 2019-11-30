<?php
declare(strict_types=1);

namespace tidy\amqp\common;

/**
 * Class Queue
 * @package tidy\amqp\common
 */
final class Queue extends Type
{
    /**
     * Declare Queue
     * @param array $config operate config
     * @return mixed|null
     */
    public function create(array $config = [])
    {
        $config = array_merge([
            'passive' => false,
            'durable' => false,
            'exclusive' => false,
            'auto_delete' => true,
            'nowait' => false,
            'arguments' => [],
            'ticket' => null
        ], $config);

        return $this->channel->queue_declare(
            $this->name,
            $config['passive'],
            $config['durable'],
            $config['exclusive'],
            $config['auto_delete'],
            $config['nowait'],
            $config['arguments'],
            $config['ticket']
        );
    }

    /**
     * Bind Exchange
     * @param string $exchangeName exchange name
     * @param array $config operate config
     * @return mixed|null
     */
    public function bind(string $exchangeName,
                         array $config = [])
    {
        $config = array_merge([
            'routing_key' => '',
            'nowait' => false,
            'arguments' => [],
            'ticket' => null
        ], $config);

        return $this->channel->queue_bind(
            $this->name,
            $exchangeName,
            $config['routing_key'],
            $config['nowait'],
            $config['arguments'],
            $config['ticket']
        );
    }

    /**
     * Unbind Exchange
     * @param string $exchangeName exchange name
     * @param array $config operate config
     * @return mixed
     */
    public function unbind(string $exchangeName,
                           array $config = [])
    {
        $config = array_merge([
            'routing_key' => '',
            'arguments' => [],
            'ticket' => null
        ], $config);

        return $this->channel->queue_unbind(
            $this->name,
            $exchangeName,
            $config['routing_key'],
            $config['arguments'],
            $config['ticket']
        );
    }

    /**
     * Purge Queue
     * @param array $config operate config
     * @return mixed|null
     */
    public function purge(array $config = [])
    {
        $config = array_merge([
            'nowait' => false,
            'ticket' => null
        ], $config);

        return $this->channel->queue_purge(
            $this->name,
            $config['nowait'],
            $config['ticket']
        );
    }

    /**
     * Delete Queue
     * @param array $config operate config
     * @return mixed|null
     */
    public function delete(array $config = [])
    {
        $config = array_merge([
            'if_unused' => false,
            'if_empty' => false,
            'nowait' => false,
            'ticket' => null
        ], $config);

        return $this->channel->queue_delete(
            $this->name,
            $config['if_unused'],
            $config['if_empty'],
            $config['nowait'],
            $config['ticket']
        );
    }

    /**
     * Get Queue
     * @param array $config operate config
     * @return mixed
     */
    public function get(array $config = [])
    {
        $config = array_merge([
            'no_ack' => false,
            'ticket' => null
        ], $config);

        return $this->channel->basic_get(
            $this->name,
            $config['no_ack'],
            $config['ticket']
        );
    }
}
