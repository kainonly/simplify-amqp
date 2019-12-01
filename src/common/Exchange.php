<?php
declare(strict_types=1);

namespace tidy\amqp\common;

/**
 * Class Exchange
 * @package tidy\amqp\common
 */
final class Exchange extends Type
{
    /**
     * Declare Exchange
     * @param string $type exchange type
     * @param array $options
     * @return mixed|null
     */
    public function create(string $type, array $options = [])
    {
        $options = array_merge([
            'passive' => false,
            'durable' => false,
            'auto_delete' => true,
            'internal' => false,
            'nowait' => false,
            'arguments' => [],
            'ticket' => null
        ], $options);

        return $this->channel->exchange_declare(
            $this->name,
            $type,
            $options['passive'],
            $options['durable'],
            $options['auto_delete'],
            $options['internal'],
            $options['nowait'],
            $options['arguments'],
            $options['ticket']
        );
    }

    /**
     * Bind Exchange
     * @param string $destination dest exchange
     * @param array $options
     * @return mixed|null
     */
    public function bind(string $destination, array $options = [])
    {
        $options = array_merge([
            'routing_key' => '',
            'nowait' => false,
            'arguments' => [],
            'ticket' => null
        ], $options);

        return $this->channel->exchange_bind(
            $destination,
            $this->name,
            $options['routing_key'],
            $options['nowait'],
            $options['arguments'],
            $options['ticket']
        );
    }

    /**
     * Unbind Exchange
     * @param string $destination dest exchange
     * @param array $options
     * @return mixed
     */
    public function unbind(string $destination, array $options = [])
    {
        $options = array_merge([
            'routing_key' => '',
            'nowait' => false,
            'arguments' => [],
            'ticket' => null
        ], $options);

        return $this->channel->exchange_unbind(
            $destination,
            $this->name,
            $options['routing_key'],
            $options['nowait'],
            $options['arguments'],
            $options['ticket']
        );
    }

    /**
     * Delete Exchange
     * @param array $options
     * @return mixed|null
     */
    public function delete(array $options = [])
    {
        $options = array_merge([
            'if_unused' => false,
            'nowait' => false,
            'ticket' => null
        ], $options);

        return $this->channel->exchange_delete(
            $this->name,
            $options['if_unused'],
            $options['nowait'],
            $options['ticket']
        );
    }
}
