<?php

namespace van\amqp\common;

use PhpAmqpLib\Channel\AMQPChannel;

/**
 * Class Type
 * @package van\amqp\common
 */
abstract class Type
{
    protected $channel;
    protected $name;

    public function __construct(AMQPChannel $channel, $name)
    {
        $this->channel = $channel;
        $this->name = $name;
    }
}
