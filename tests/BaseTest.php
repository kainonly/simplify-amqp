<?php
declare(strict_types=1);

namespace SimplifyTests;

use Exception;
use PHPUnit\Framework\TestCase;
use Simplify\AMQP\AMQPClient;
use Simplify\AMQP\AMQPManager;

abstract class BaseTest extends TestCase
{
    /**
     * @var AMQPClient
     */
    protected AMQPClient $client;

    public function setUp(): void
    {
        $this->client = new AMQPClient(
            getenv('host'),
            (int)getenv('port'),
            getenv('username'),
            getenv('password'),
            '/',
            [
                'insist' => false,
                'login_method' => 'AMQPLAIN',
                'login_response' => null,
                'locale' => 'zh_CN',
                'connection_timeout' => 5.0,
                'read_write_timeout' => 5.0,
                'context' => null,
                'keepalive' => true,
                'heartbeat' => 3.0,
                'channel_rpc_timeout' => 5.0,
                'ssl_protocol' => null
            ]
        );
    }

    public function testGetConnection(): void
    {
        $connected = $this->client
            ->getAMQPStreamConnection()
            ->getConnection()
            ->isConnected();
        $this->assertTrue($connected);
    }

    public function testGetChannelConnection(): void
    {
        try {
            $this->client->channel(function (AMQPManager $manager) {
                $connected = $manager->getChannel()->getConnection()->isConnected();
                $this->assertTrue($connected);
            });
        } catch (Exception $e) {
            $this->expectErrorMessage($e->getMessage());
        }
    }
}