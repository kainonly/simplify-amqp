<?php
declare(strict_types=1);

namespace Simplify\AMQP\Common;

class QueueCreateOption
{
    private bool $passive = false;
    private bool $durable = true;
    private bool $exclusive = false;
    private bool $auto_delete = false;
    private array $arguments = [];

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
    public function isExclusive(): bool
    {
        return $this->exclusive;
    }

    /**
     * @param bool $exclusive
     */
    public function setExclusive(bool $exclusive): void
    {
        $this->exclusive = $exclusive;
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
     * How long a message published to a queue can live before it is discarded
     * @param int $milliseconds
     */
    public function setMessageTTL(int $milliseconds): void
    {
        $this->arguments['x-message-ttl'] = $milliseconds;
    }

    /**
     * How long a queue can be unused for before it is automatically deleted
     * @param int $milliseconds
     */
    public function setAutoExpire(int $milliseconds): void
    {
        $this->arguments['x-expires'] = $milliseconds;
    }

    /**
     * How many (ready) messages a queue can contain before it starts to drop them from its head.
     * @param int $length
     */
    public function setMaxLength(int $length): void
    {
        $this->arguments['x-max-length'] = $length;
    }

    /**
     * Total body size for ready messages a queue can contain before it starts to drop them from its head.
     * @param int $sizeof
     */
    public function setMaxLengthBytes(int $sizeof): void
    {
        $this->arguments['x-max-length-bytes'] = $sizeof;
    }

    /**
     * Sets the queue overflow behaviour. This determines what happens to messages when the maximum length of a queue is reached
     * @param array $values
     */
    public function setOverflow(array $values): void
    {
        $this->arguments['x-overflow'] = implode(',', $values);
    }

    /**
     * @param string $exchange
     */
    public function setDeadLetterExchange(string $exchange): void
    {
        $this->arguments['x-dead-letter-exchange'] = $exchange;
    }


}