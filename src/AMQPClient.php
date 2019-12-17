<?php
declare(strict_types=1);

namespace simplify\amqp;

use Closure;
use Exception;
use PhpAmqpLib\Connection\AMQPStreamConnection;

/**
 * Class Client
 * @package simplify\amqp
 */
final class AMQPClient
{
    /**
     * AMQP Connection
     * @var AMQPStreamConnection
     */
    private AMQPStreamConnection $connection;

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
     * create amqp channel
     * @param Closure $closure
     * @param array $options channel options
     * @throws Exception
     */
    public function channel(Closure $closure, array $options = []): void
    {
        $options = array_merge([
            'channel_id' => null,
            'reply_code' => 0,
            'reply_text' => '',
            'method_sig' => [0, 0]
        ], $options);
        $channel = $this->connection
            ->channel($options['channel_id']);
        $closure(new AMQPManager($channel));
        $channel->close(
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
     * create amqp channel with transaction
     * @param Closure $closure
     * @param array $options
     * @throws Exception
     */
    public function channeltx(Closure $closure, array $options = []): void
    {
        $options = array_merge([
            'channel_id' => null,
            'reply_code' => 0,
            'reply_text' => '',
            'method_sig' => [0, 0]
        ], $options);

        $channel = $this->connection
            ->channel($options['channel_id']);
        $channel->tx_select();
        if ($closure(new AMQPManager($channel))) {
            $channel->tx_commit();
        } else {
            $channel->tx_rollback();
        }
        $channel->close(
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
}
