<?php
declare(strict_types=1);

namespace tests;

class Exchange extends BaseTest
{
    public function testExchangeDeclare()
    {
        try {
            $this->client->channel(function () {
                $this->client
                    ->exchange('tidy-test')
                    ->setDeclare('topic', [
                        'nowait' => false
                    ]);
                $this->assertTrue(true);
            });
        } catch (\Exception $e) {
            $this->expectErrorMessage($e->getMessage());
        }
    }

}