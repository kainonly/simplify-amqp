<?php
declare(strict_types=1);

namespace simplify\amqp\common;

use Closure;

/**
 * Class Consumer
 * @package simplify\amqp\common
 * @inheritDoc
 */
final class Consumer extends Type
{
    /**
     * Subscribe
     * @param string $queueName queue name
     * @param Closure $subscribe Subscribe
     * @param array $options
     * @return mixed|string
     */
    public function subscribe(string $queueName, Closure $subscribe, array $options = [])
    {
        $options = array_merge([
            'no_local' => false,
            'no_ack' => false,
            'exclusive' => false,
            'nowait' => false,
            'ticket' => null,
            'arguments' => []
        ], $options);

        return $this->channel->basic_consume(
            $queueName,
            $this->name,
            $options['no_local'],
            $options['no_ack'],
            $options['exclusive'],
            $options['nowait'],
            $subscribe,
            $options['ticket'],
            $options['arguments']
        );
    }

    /**
     * Unsubscribe
     * @param array $options
     * @return mixed
     */
    public function unsubscribe(array $options = [])
    {
        $options = array_merge([
            'nowait' => false,
            'noreturn' => false
        ], $options);

        return $this->channel->basic_cancel(
            $this->name,
            $options['nowait'],
            $options['noreturn']
        );
    }
}
