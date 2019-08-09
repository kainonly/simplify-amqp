<?php

namespace van\amqp\common;

use PhpAmqpLib\Channel\AMQPChannel;

/**
 * Class Consumer
 * @package van\amqp\common
 */
final class Consumer extends Type
{
    /**
     * 启用消费者
     * @param string $queue 队列名称
     * @param array $config 操作配置
     * @return mixed|string
     */
    public function start($queue, array $config = [])
    {
        $config = array_merge([
            'no_local' => false,
            'no_ack' => false,
            'exclusive' => false,
            'nowait' => false,
            'callback' => null,
            'ticket' => null,
            'arguments' => []
        ], $config);

        return $this->channel->basic_consume(
            $queue,
            $this->name,
            $config['no_local'],
            $config['no_ack'],
            $config['exclusive'],
            $config['nowait'],
            $config['callback'],
            $config['ticket'],
            $config['arguments']
        );
    }

    /**
     * 结束消费者
     * @param array $config 操作配置
     * @return mixed
     */
    public function cancel(array $config = [])
    {
        $config = array_merge([
            'nowait' => false,
            'noreturn' => false
        ], $config);

        return $this->channel->basic_cancel(
            $this->name,
            $config['nowait'],
            $config['noreturn']
        );
    }
}
