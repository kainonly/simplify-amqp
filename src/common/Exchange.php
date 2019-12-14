<?php
declare(strict_types=1);

namespace simplify\amqp\common;
/**
 * Class Exchange
 * @package simplify\amqp\common
 */
final class Exchange extends Type
{
    /**
     * declare exchange
     * @param string $type exchange type
     * @param array $options exchange options
     */
    public function setDeclare(
        string $type,
        array $options = []
    ): void
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

        $this->channel->exchange_declare(
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
     * bind exchange
     * @param string $destination dest exchange
     * @param array $options
     */
    public function bind(
        string $destination,
        array $options = []
    ): void
    {
        $options = array_merge([
            'routing_key' => '',
            'nowait' => false,
            'arguments' => [],
            'ticket' => null
        ], $options);

        $this->channel->exchange_bind(
            $destination,
            $this->name,
            $options['routing_key'],
            $options['nowait'],
            $options['arguments'],
            $options['ticket']
        );
    }

    /**
     * unbind exchange
     * @param string $destination dest exchange
     * @param array $options
     */
    public function unbind(string $destination, array $options = []): void
    {
        $options = array_merge([
            'routing_key' => '',
            'nowait' => false,
            'arguments' => [],
            'ticket' => null
        ], $options);

        $this->channel->exchange_unbind(
            $destination,
            $this->name,
            $options['routing_key'],
            $options['nowait'],
            $options['arguments'],
            $options['ticket']
        );
    }

    /**
     * delete Exchange
     * @param array $options
     */
    public function delete(array $options = []): void
    {
        $options = array_merge([
            'if_unused' => false,
            'nowait' => false,
            'ticket' => null
        ], $options);

        $this->channel->exchange_delete(
            $this->name,
            $options['if_unused'],
            $options['nowait'],
            $options['ticket']
        );
    }
}
