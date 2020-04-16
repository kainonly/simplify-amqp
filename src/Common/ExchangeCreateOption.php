<?php
declare(strict_types=1);

namespace Simplify\AMQP\Common;

/**
 * Class ExchangeCreateOption
 * @package Simplify\AMQP\Common
 */
class ExchangeCreateOption
{
    /**
     * @var ExchangeType
     */
    private ExchangeType $type;
    /**
     * @var bool
     */
    private bool $passive = false;
    /**
     * @var bool
     */
    private bool $durable = true;
    /**
     * @var bool
     */
    private bool $auto_delete = false;
    /**
     * @var bool
     */
    private bool $internal = false;
    /**
     * @var array
     */
    private array $arguments = [];

    /**
     * ExchangeCreateOption constructor.
     */
    public function __construct()
    {
        $this->type = ExchangeType::DIRECT();
    }

    /**
     * @return ExchangeType
     */
    public function getType(): ExchangeType
    {
        return $this->type;
    }

    /**
     * @param ExchangeType $type
     */
    public function setType(ExchangeType $type): void
    {
        $this->type = $type;
    }

    /**
     * @return bool
     */
    public function isPassive(): bool
    {
        return $this->passive;
    }

    /**
     * @param bool $passive
     */
    public function setPassive(bool $passive): void
    {
        $this->passive = $passive;
    }

    /**
     * @return bool
     */
    public function isDurable(): bool
    {
        return $this->durable;
    }

    /**
     * @param bool $durable
     */
    public function setDurable(bool $durable): void
    {
        $this->durable = $durable;
    }

    /**
     * @return bool
     */
    public function isAutoDelete(): bool
    {
        return $this->auto_delete;
    }

    /**
     * @param bool $auto_delete
     */
    public function setAutoDelete(bool $auto_delete): void
    {
        $this->auto_delete = $auto_delete;
    }

    /**
     * @return bool
     */
    public function isInternal(): bool
    {
        return $this->internal;
    }

    /**
     * @param bool $internal
     */
    public function setInternal(bool $internal): void
    {
        $this->internal = $internal;
    }

    /**
     * If messages to this exchange cannot otherwise be routed,
     * send them to the alternate exchange named here.
     * @param string $value
     */
    public function setAlternateExchange(string $value): void
    {
        $this->arguments['alternate-exchange'] = $value;
    }

    /**
     * @return array
     */
    public function getArguments(): array
    {
        return $this->arguments;
    }

    /**
     * @param string $key
     * @param $value
     */
    public function appendArgument(string $key, $value): void
    {
        $this->arguments[$key] = $value;
    }
}