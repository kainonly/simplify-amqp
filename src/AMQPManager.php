<?php
declare(strict_types=1);

namespace simplify\amqp;

use PhpAmqpLib\Channel\AMQPChannel;
use PhpAmqpLib\Message\AMQPMessage;
use simplify\amqp\common\Consumer;
use simplify\amqp\common\Exchange;
use simplify\amqp\common\Queue;

final class AMQPManager
{
    /**
     * AMQP Channel
     * @var AMQPChannel
     */
    private $channel;

    /**
     * create amqp message
     * @param string $text Message Body
     * @param array $options options
     * @return AMQPMessage
     */
    public static function message(
        string $text,
        array $options = []
    ): AMQPMessage
    {
        return new AMQPMessage(
            $text,
            $options
        );
    }

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
    public function getChannel()
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
        $options = array_merge([
            'mandatory' => false,
            'immediate' => false,
            'ticket' => null
        ], $options);
        $this->channel->basic_publish(
            $message,
            $exchange,
            $routing_key,
            $options['mandatory'],
            $options['immediate'],
            $options['ticket']
        );
    }

    /**
     * Ack Message
     * @param int $delivery_tag Tag
     * @param bool $multiple Multiple
     */
    public function ack(
        int $delivery_tag,
        bool $multiple = false
    ): void
    {
        $this->channel->basic_ack(
            $delivery_tag,
            $multiple
        );
    }

    /**
     * Reject Message
     * @param int $delivery_tag Tag
     * @param bool $requeue Reset Message
     */
    public function reject(
        int $delivery_tag,
        bool $requeue = false
    ): void
    {
        $this->channel->basic_reject(
            $delivery_tag,
            $requeue
        );
    }

    /**
     * Nack Message
     * @param int $delivery_tag Tag
     * @param bool $multiple Mulitple
     * @param bool $requeue Reset Message
     */
    public function nack(
        int $delivery_tag,
        bool $multiple = false,
        bool $requeue = false
    ): void
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
     * @return Exchange
     */
    public function exchange(string $name): Exchange
    {
        return new Exchange($this->channel, $name);
    }

    /**
     * Create Queue Operate
     * @param string $name queue name
     * @return Queue
     */
    public function queue(string $name): Queue
    {
        return new Queue($this->channel, $name);
    }

    /**
     * Create Consumer Operate
     * @param string $name cusumer name
     * @return Consumer
     */
    public function consumer(string $name): Consumer
    {
        return new Consumer($this->channel, $name);
    }
}