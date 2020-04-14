<?php
declare(strict_types=1);

namespace Simplify\AMQP\Factory;
/**
 * Class Exchange
 * @package Simplify\AMQP\Factory
 */
class ExchangeFactory extends BaseFactory
{
    /**
     * declare exchange
     * @param string $type exchange type
     * @param array $options exchange options
     */
    public function setDeclare(string $type, array $options = []): void
    {
        $this->channel->exchange_declare(
            $this->name,
            $type,
            $options['passive'] ?? false,
            $options['durable'] ?? true,
            $options['auto_delete'] ?? false,
            $options['internal'] ?? false,
            false,
            $options['arguments'] ?? []
        );
    }

    /**
     * bind exchange
     * @param string $destination dest exchange
     * @param array $options
     */
    public function bind(string $destination, array $options = []): void
    {
        $this->channel->exchange_bind(
            $destination,
            $this->name,
            $options['routing_key'] ?? '',
            false,
            $options['arguments'] ?? []
        );
    }

    /**
     * unbind exchange
     * @param string $destination dest exchange
     * @param array $options
     */
    public function unbind(string $destination, array $options = []): void
    {
        $this->channel->exchange_unbind(
            $destination,
            $this->name,
            $options['routing_key'] ?? '',
            false,
            $options['arguments'] ?? [],
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
