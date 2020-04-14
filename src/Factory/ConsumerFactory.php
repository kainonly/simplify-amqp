<?php
declare(strict_types=1);

namespace Simplify\AMQP\Factory;

use Closure;

/**
 * Class Consumer
 * @package Simplify\AMQP\Factory
 * @inheritDoc
 */
class ConsumerFactory extends BaseFactory
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
        return $this->channel->basic_consume(
            $queueName,
            $this->name,
            $options['no_local'] ?? false,
            $options['no_ack'] ?? false,
            $options['exclusive'] ?? false,
            false,
            $subscribe,
            null,
            $options['arguments'] ?? []
        );
    }

    /**
     * Unsubscribe
     * @return mixed
     */
    public function unsubscribe()
    {
        return $this->channel->basic_cancel($this->name);
    }
}
