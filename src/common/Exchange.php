<?php

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
     * @param array $config operate config
     * @return mixed|null
     */
    public function create(string $type,
                           array $config = [])
    {
        $config = array_merge([
            'passive' => false,
            'durable' => false,
            'auto_delete' => true,
            'internal' => false,
            'nowait' => false,
            'arguments' => [],
            'ticket' => null
        ], $config);

        return $this->channel->exchange_declare(
            $this->name,
            $type,
            $config['passive'],
            $config['durable'],
            $config['auto_delete'],
            $config['internal'],
            $config['nowait'],
            $config['arguments'],
            $config['ticket']
        );
    }

    /**
     * Bind Exchange
     * @param string $destination dest exchange
     * @param array $config config
     * @return mixed|null
     */
    public function bind(string $destination,
                         array $config = [])
    {
        $config = array_merge([
            'routing_key' => '',
            'nowait' => false,
            'arguments' => [],
            'ticket' => null
        ], $config);

        return $this->channel->exchange_bind(
            $destination,
            $this->name,
            $config['routing_key'],
            $config['nowait'],
            $config['arguments'],
            $config['ticket']
        );
    }

    /**
     * Unbind Exchange
     * @param string $destination dest exchange
     * @param array $config operate config
     * @return mixed
     */
    public function unbind(string $destination,
                           array $config = [])
    {
        $config = array_merge([
            'routing_key' => '',
            'nowait' => false,
            'arguments' => [],
            'ticket' => null
        ], $config);

        return $this->channel->exchange_unbind(
            $destination,
            $this->name,
            $config['routing_key'],
            $config['nowait'],
            $config['arguments'],
            $config['ticket']
        );
    }

    /**
     * Delete Exchange
     * @param array $config operate config
     * @return mixed|null
     */
    public function delete(array $config = [])
    {
        $config = array_merge([
            'if_unused' => false,
            'nowait' => false,
            'ticket' => null
        ], $config);

        return $this->channel->exchange_delete(
            $this->name,
            $config['if_unused'],
            $config['nowait'],
            $config['ticket']
        );
    }
}
