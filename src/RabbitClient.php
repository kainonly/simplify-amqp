<?php

namespace tidy\amqp;

use Closure;
use PhpAmqpLib\Channel\AMQPChannel;
use PhpAmqpLib\Connection\AMQPStreamConnection;
use PhpAmqpLib\Message\AMQPMessage;
use tidy\amqp\common\Consumer;
use tidy\amqp\common\Exchange;
use tidy\amqp\common\Queue;

/**
 * Class RabbitClient
 * @package tidy\amqp
 */
final class RabbitClient
{
    /**
     * AMQP Connection
     * @var AMQPStreamConnection
     */
    private $connection;

    /**
     * AMQP Channel
     * @var AMQPChannel
     */
    private $channel;

    /**
     * RabbitClient constructor.
     * @param array $options Connection Options
     */
    public function __construct(array $options = [])
    {
        $_options = array_merge([
            'virualhost' => '/',
            'insist' => false,
            'login_method' => 'AMQPLAIN',
            'login_response' => null,
            'locale' => 'en_US',
            'connection_timeout' => 3.0,
            'read_write_timeout' => 3.0,
            'context' => null,
            'keepalive' => false,
            'heartbeat' => 0,
            'channel_rpc_timeout' => 0.0
        ], $options);

        $this->connection = new AMQPStreamConnection(
            $_options['hostname'],
            $_options['port'],
            $_options['username'],
            $_options['password'],
            $_options['virualhost'],
            $_options['insist'],
            $_options['login_method'],
            $_options['login_response'],
            $_options['locale'],
            $_options['connection_timeout'],
            $_options['read_write_timeout'],
            $_options['context'],
            $_options['keepalive'],
            $_options['heartbeat'],
            $_options['channel_rpc_timeout']
        );
    }

    /**
     * Create Channel
     * @param Closure $closure
     * @param array $config Operate Config
     * @throws \Exception
     */
    public function channel(Closure $closure,
                            array $config = [])
    {
        $config = array_merge([
            'transaction' => false,
            'channel_id' => null,
            'reply_code' => 0,
            'reply_text' => '',
            'method_sig' => [0, 0]
        ], $config);

        $this->channel = $this->connection
            ->channel($config['channel_id']);

        if ($config['transaction']) {
            $this->channel->tx_select();
            $result = $closure($this->channel);

            if ($result) {
                $this->channel->tx_commit();
            } else {
                $this->channel->tx_rollback();
            }
        } else {
            $closure($this->channel);
        }

        $this->channel->close(
            $config['reply_code'],
            $config['reply_text'],
            $config['method_sig']
        );

        $this->connection->close(
            $config['reply_code'],
            $config['reply_text'],
            $config['method_sig']
        );
    }

    /**
     * get Channel
     * @return AMQPChannel
     */
    public function getChannel()
    {
        return $this->channel;
    }

    /**
     * Create Message
     * @param string|array $text Message Body
     * @param array $config Operate Config
     * @return AMQPMessage
     */
    public function message($text = '',
                            array $config = [])
    {
        return new AMQPMessage(
            is_array($text) ? json_encode($text) : $text,
            $config
        );
    }

    /**
     * Publish Message
     * @param string|array $text Message Body
     * @param array $config Operate Config
     */
    public function publish($text = '',
                            array $config = [])
    {

        $config = array_merge([
            'exchange' => '',
            'routing_key' => '',
            'mandatory' => false,
            'immediate' => false,
            'ticket' => null
        ], $config);

        $this->channel->basic_publish(
            $this->message($text),
            $config['exchange'],
            $config['routing_key'],
            $config['mandatory'],
            $config['immediate'],
            $config['ticket']
        );
    }

    /**
     * Ack Message
     * @param string $delivery_tag Tag
     * @param bool $multiple Multiple
     */
    public function ack(string $delivery_tag,
                        bool $multiple = false)
    {
        $this->channel->basic_ack($delivery_tag, $multiple);
    }

    /**
     * Reject Message
     * @param string $delivery_tag Tag
     * @param bool $requeue Reset Message
     */
    public function reject(string $delivery_tag,
                           bool $requeue = false)
    {
        $this->channel->basic_reject($delivery_tag, $requeue);
    }

    /**
     * Nack Message
     * @param string $delivery_tag Tag
     * @param bool $multiple Mulitple
     * @param bool $requeue Reset Message
     */
    public function nack(string $delivery_tag,
                         bool $multiple = false,
                         bool $requeue = false)
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
     * @param string $exchangeName exchange name
     * @return Exchange
     */
    public function exchange(string $exchangeName)
    {
        return new Exchange(
            $this->channel,
            $exchangeName
        );
    }

    /**
     * Create Queue Operate
     * @param string $queueName queue name
     * @return Queue
     */
    public function queue(string $queueName)
    {
        return new Queue(
            $this->channel,
            $queueName
        );
    }

    /**
     * Create Consumer Operate
     * @param string $consumer cusumer name
     * @return Consumer
     */
    public function consumer(string $consumerName)
    {
        return new Consumer(
            $this->channel,
            $consumerName
        );
    }
}
