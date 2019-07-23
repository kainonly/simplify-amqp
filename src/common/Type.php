<?php

namespace think\amqp\common;

use PhpAmqpLib\Channel\AMQPChannel;

/**
 * Class Type
 * @package think\amqp\common
 */
class Type
{
    protected $channel;
    protected $name;

    public function __construct(AMQPChannel $channel, $name)
    {
        $this->channel = $channel;
        $this->name = $name;
    }
}
