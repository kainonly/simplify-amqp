<?php
declare(strict_types=1);

namespace Simplify\AMQP\Factory;

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
            $option->getArguments()
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
