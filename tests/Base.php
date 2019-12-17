<?php
declare(strict_types=1);

namespace tests;

use PHPUnit\Framework\TestCase;
use simplify\amqp\AMQPClient;

abstract class Base extends TestCase
{
    /**
     * @var string
     */
    protected $hostname;
    /**
     * @var int
     */
    protected $port;
    /**
     * @var string
     */
    protected $username;
    /**
     * @var string
     */
    protected $password;
    /**
     * @var AMQPClient
     */
    protected $client;

    /**
     * set Up
     */
    protected function setUp(): void
    {
        $this->hostname = getenv('hostname');
        $this->port = (int)getenv('port');
        $this->username = getenv('username');
        $this->password = getenv('password');
        $this->client = new AMQPClient(
            $this->hostname,
            $this->port,
            $this->username,
            $this->password
        );
    }
}