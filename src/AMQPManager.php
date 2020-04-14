<?php
declare(strict_types=1);

namespace Simplify\AMQP;

use PhpAmqpLib\Channel\AMQPChannel;
use PhpAmqpLib\Message\AMQPMessage;
use Simplify\AMQP\factory\ConsumerFactory;
use Simplify\AMQP\factory\ExchangeFactory;
use Simplify\AMQP\factory\QueueFactory;

class AMQPManager
{
    /**
     * AMQP Channel
     * @var AMQPChannel
     */
    private AMQPChannel $channel;

    /**
     * Manager constructor.
     * @param AMQPChannel $channel
     */
    public function __construct(AMQPChannel $channel)
    {
        $this->channel = $channel;
    }

    /**
     * get channel
     * @return AMQPChannel
     */
    public function getChannel(): AMQPChannel
    {
        return $this->channel;
    }

    /**
     * publish message
     * @param AMQPMessage $message Message Body
     * @param string $exchange
     * @param string $routing_key
     * @param array $options
     */
    public function publish(
        AMQPMessage $message,
        string $exchange,
        string $routing_key,
        array $options = []
    ): void
    {
        $this->channel->basic_publish(
            $message,
            $exchange,
            $routing_key,
            $options['mandatory'] ?? false,
            $options['immediate'] ?? false,
        );
    }

    /**
     * Ack Message
     * @param int $delivery_tag Tag
     * @param bool $multiple Multiple
     */
    public function ack(int $delivery_tag, bool $multiple = false): void
    {
        $this->channel->basic_ack($delivery_tag, $multiple);
    }

    /**
     * Reject Message
     * @param int $delivery_tag Tag
     * @param bool $requeue Reset Message
     */
    public function reject(int $delivery_tag, bool $requeue = false): void
    {
        $this->channel->basic_reject($delivery_tag, $requeue);
    }

    /**
     * Nack Message
     * @param int $delivery_tag Tag
     * @param bool $multiple Mulitple
     * @param bool $requeue Reset Message
     */
    public function nack(int $delivery_tag, bool $multiple = false, bool $requeue = false): void
    {
        $this->channel->basic_nack(
            $delivery_tag,
            $multiple,
            $requeue
        );
    }

    /**
     * Revover Message
     * @param bool $requeue Reset Message
     * @return mixed
     */
    public function revover(bool $requeue = false)
    {
        return $this->channel->basic_recover($requeue);
    }

    /**
     * Create Exchange Operate
     * @param string $name exchange name
     * @return ExchangeFactory
     */
    public function exchange(string $name): ExchangeFactory
    {
        return new ExchangeFactory($this->channel, $name);
    }

    /**
     * Create Queue Operate
     * @param string $name queue name
     * @return QueueFactory
     */
    public function queue(string $name): QueueFactory
    {
        return new QueueFactory($this->channel, $name);
    }

    /**
     * Create Consumer Operate
     * @param string $name cusumer name
     * @return ConsumerFactory
     */
    public function consumer(string $name): ConsumerFactory
    {
        return new ConsumerFactory($this->channel, $name);
    }
}