<?php
declare(strict_types=1);

namespace Simplify\AMQP\Factory;

use Closure;
use PhpAmqpLib\Wire\AMQPTable;

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
     * @param bool $no_local
     * @param bool $no_ack
     * @param bool $exclusive
     * @param array $arguments
     * @return string
     */
    public function subscribe(
        string $queueName,
        Closure $subscribe,
        bool $no_local = false,
        bool $no_ack = false,
        bool $exclusive = false,
        array $arguments = []
    ): string
    {
        return $this->channel->basic_consume(
            $queueName,
            $this->name,
            $no_local,
            $no_ack,
            $exclusive,
            false,
            $subscribe,
            null,
            new AMQPTable($arguments)
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
