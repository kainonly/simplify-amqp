<?php
declare(strict_types=1);

namespace tidy\amqp\common;

use PhpAmqpLib\Channel\AMQPChannel;

/**
 * Class Type
 * @package tidy\amqp\common
 */
abstract class Type
{
    /**
     * Operate Channel
     * @var AMQPChannel
     */
    protected $channel;

    /**
     * Operate Name
     * @var string
     */
    protected $name;

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
