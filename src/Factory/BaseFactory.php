<?php
declare(strict_types=1);

namespace Simplify\AMQP\Factory;

use PhpAmqpLib\Channel\AMQPChannel;

/**
 * Class Type
 * @package Simplify\AMQP\Factory
 */
abstract class BaseFactory
{
    /**
     * Operate Channel
     * @var AMQPChannel
     */
    protected AMQPChannel $channel;

    /**
     * Operate Name
     * @var string
     */
    protected string $name;

    /**
     * Type constructor.
     * @param AMQPChannel $channel
     * @param string $name
     */
    public function __construct(AMQPChannel $channel, string $name)
    {
        $this->channel = $channel;
        $this->name = $name;
    }
}
