<?php
declare(strict_types=1);

namespace Simplify\AMQP\Common;

use MyCLabs\Enum\Enum;

/**
 * Class ExchangeType
 * @package Simplify\AMQP\Common
 * @method static ExchangeType DIRECT()
 * @method static ExchangeType FANOUT()
 * @method static ExchangeType HEADERS()
 * @method static ExchangeType TOPIC()
 */
class ExchangeType extends Enum
{
    private const DIRECT = 'direct';
    private const FANOUT = 'fanout';
    private const HEADERS = 'headers';
    private const TOPIC = 'topic';
}