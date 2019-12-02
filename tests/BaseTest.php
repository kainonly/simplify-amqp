<?php
declare(strict_types=1);

namespace tests;

use PHPUnit\Framework\TestCase;
use tidy\amqp\Client;

class BaseTest extends TestCase
{
    /**
     * @var Client
     */
    protected $client;

    protected function setUp(): void
    {
        $this->client = new Client(
            getenv('hostname'),
            (int)getenv('port'),
            getenv('username'),
            getenv('password')
        );
    }
}