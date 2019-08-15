<?php

namespace tidy\amqp\common;

use Closure;

/**
 * Class Consumer
 * @package tidy\amqp\common
 */
final class Consumer extends Type
{
    /**
     * Create Consumer
     * @param string $queueName queue name
     * @param Closure $subscribe Subscribe
     * @param array $config operate config
     * @return mixed|string
     */
    public function create(string $queueName,
                           Closure $subscribe,
                           array $config = [])
    {
        $config = array_merge([
            'no_local' => false,
            'no_ack' => false,
            'exclusive' => false,
            'nowait' => false,
            'ticket' => null,
            'arguments' => []
        ], $config);

        return $this->channel->basic_consume(
            $queueName,
            $this->name,
            $config['no_local'],
            $config['no_ack'],
            $config['exclusive'],
            $config['nowait'],
            $subscribe,
            $config['ticket'],
            $config['arguments']
        );
    }

    /**
     * Unsubscribe
     * @param array $config operate config
     * @return mixed
     */
    public function unsubscribe(array $config = [])
    {
        $config = array_merge([
            'nowait' => false,
            'noreturn' => false
        ], $config);

        return $this->channel->basic_cancel(
            $this->name,
            $config['nowait'],
            $config['noreturn']
        );
    }
}
