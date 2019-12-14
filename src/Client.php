<?php
declare(strict_types=1);

namespace simplify\amqp;

use Closure;
use Exception;
use PhpAmqpLib\Channel\AMQPChannel;
use PhpAmqpLib\Connection\AMQPStreamConnection;
use PhpAmqpLib\Message\AMQPMessage;
use simplify\amqp\common\Consumer;
use simplify\amqp\common\Exchange;
use simplify\amqp\common\Queue;

/**
 * Class Client
 * @package simplify\amqp
 */
final class Client
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
     * Client constructor.
     * @param string $hostname
     * @param int $port
     * @param string $username
     * @param string $password
     * @param string $virualhost
     * @param array $options Connection Options
     */
    public function __construct(
        string $hostname,
        int $port,
        string $username,
        string $password,
        string $virualhost = '/',
        array $options = []
    )
    {
        $options = array_merge([
            'insist' => false,
            'login_method' => 'AMQPLAIN',
            'login_response' => null,
            'locale' => 'en_US',
            'connection_timeout' => 3.0,
            'read_write_timeout' => 3.0,
            'context' => null,
            'keepalive' => false,
            'heartbeat' => 0,
            'channel_rpc_timeout' => 0.0,
            'ssl_protocol' => null
        ], $options);
        $this->connection = new AMQPStreamConnection(
            $hostname,
            $port,
            $username,
            $password,
            $virualhost,
            $options['insist'],
            $options['login_method'],
            $options['login_response'],
            $options['locale'],
            $options['connection_timeout'],
            $options['read_write_timeout'],
            $options['context'],
            $options['keepalive'],
            $options['heartbeat'],
            $options['channel_rpc_timeout'],
            $options['ssl_protocol']
        );
    }

    /**
     * get connection
     * @return AMQPStreamConnection
     */
    public function getConnection(): AMQPStreamConnection
    {
        return $this->connection;
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
     * create amqp channel
     * @param Closure $closure
     * @param array $options channel options
     * @throws Exception
     */
    public function channel(Closure $closure, array $options = [])
    {
        $options = array_merge([
            'transaction' => false,
            'channel_id' => null,
            'reply_code' => 0,
            'reply_text' => '',
            'method_sig' => [0, 0]
        ], $options);
        $this->channel = $this->connection
            ->channel($options['channel_id']);

        if (!empty($options['transaction']) && $options['transaction'] == true) {
            $this->channel->tx_select();
            if ($closure($this->channel)) {
                $this->channel->tx_commit();
            } else {
                $this->channel->tx_rollback();
            }
        } else {
            $closure($this->channel);
        }

        $this->channel->close(
            $options['reply_code'],
            $options['reply_text'],
            $options['method_sig']
        );
        $this->connection->close(
            $options['reply_code'],
            $options['reply_text'],
            $options['method_sig']
        );
    }

    /**
     * create amqp message
     * @param string $text Message Body
     * @param array $options options
     * @return AMQPMessage
     */
    public function message(string $text, array $options = []): AMQPMessage
    {
        return new AMQPMessage(
            $text,
            $options
        );
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
     * @param string $delivery_tag Tag
     * @param bool $multiple Multiple
     */
    public function ack(
        string $delivery_tag,
        bool $multiple = false
    )
    {
        $this->channel->basic_ack(
            $delivery_tag,
            $multiple
        );
    }

    /**
     * Reject Message
     * @param string $delivery_tag Tag
     * @param bool $requeue Reset Message
     */
    public function reject(
        string $delivery_tag,
        bool $requeue = false
    )
    {
        $this->channel->basic_reject(
            $delivery_tag,
            $requeue
        );
    }

    /**
     * Nack Message
     * @param string $delivery_tag Tag
     * @param bool $multiple Mulitple
     * @param bool $requeue Reset Message
     */
    public function nack(
        string $delivery_tag,
        bool $multiple = false,
        bool $requeue = false
    )
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
