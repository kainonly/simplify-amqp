<?php
declare(strict_types=1);

namespace SimplifyTests;

use PHPUnit\Framework\TestCase;
use Simplify\AMQP\AMQPClient;

abstract class BaseTest extends TestCase
{
    /**
     * @var AMQPClient
     */
    protected AMQPClient $client;

    public function __construct($name = null, array $data = [], $dataName = '')
    {
        parent::__construct($name, $data, $dataName);
        $this->client = new AMQPClient(
            getenv('host'),
            (int)getenv('port'),
            getenv('username'),
            getenv('password')
        );
    }
}