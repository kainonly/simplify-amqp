<?php
declare(strict_types=1);

namespace Simplify\AMQP;

use Closure;
use Exception;
use PhpAmqpLib\Connection\AMQPStreamConnection;

/**
 * Class Client
 * @package Simplify\AMQP
 */
class AMQPClient
{
    /**
     * AMQP Connection
     * @var AMQPStreamConnection
     */
    private AMQPStreamConnection $connection;

    /**
     * Client constructor.
     * @param string $host
     * @param int $port
     * @param string $username
     * @param string $password
     * @param string $vhost
     * @param array $options Connection Options
     */
    public function __construct(
        string $host,
        int $port,
        string $username,
        string $password,
        string $vhost = '/',
        array $options = []
    )
    {
        $this->connection = new AMQPStreamConnection(
            $host,
            $port,
            $username,
            $password,
            $vhost,
            $options['insist'] ?? false,
            $options['login_method'] ?? 'AMQPLAIN',
            $options['login_response'] ?? null,
            $options['locale'] ?? 'en_US',
            $options['connection_timeout'] ?? 3.0,
            $options['read_write_timeout'] ?? 3.0,
            $options['context'] ?? null,
            $options['keepalive'] ?? false,
            $options['heartbeat'] ?? 0,
            $options['channel_rpc_timeout'] ?? 0.0,
            $options['ssl_protocol'] ?? null
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
     * @param Closure $closure
     * @param string|null $id
     * @param bool $wait
     * @throws Exception
     */
    public function channel(Closure $closure, string $id = null, bool $wait = false): void
    {
        $channel = $this->connection->channel($id);
        $closure(new AMQPManager($channel));
        $channel->close();
        if (!$wait) {
            $this->connection->close();
        }
    }

    /**
     * create amqp channel with transaction
     * @param Closure $closure
     * @param string|null $id
     * @param bool $wait
     * @throws Exception
     */
    public function channeltx(Closure $closure, string $id = null, bool $wait = false): void
    {
        $channel = $this->connection->channel($id);
        $channel->tx_select();
        if ($closure(new AMQPManager($channel))) {
            $channel->tx_commit();
        } else {
            $channel->tx_rollback();
        }
        $channel->close();
        if (!$wait) {
            $this->connection->close();
        }
    }
}
