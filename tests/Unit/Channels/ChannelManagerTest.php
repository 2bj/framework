<?php

declare(strict_types=1);

namespace Tests\Unit\Channels;

use Tests\TestCase;
use FondBot\Helpers\Str;
use FondBot\Channels\Channel;
use FondBot\Channels\ChannelManager;

class ChannelManagerTest extends TestCase
{
    public function test_create()
    {
        $name = 'fake';
        $parameters = [
            'driver' => 'fake',
            'token' => Str::random(),
        ];

        $manager = new ChannelManager();

        $manager->add($name, $parameters);

        $result = $manager->create($name);

        $this->assertInstanceOf(Channel::class, $result);
        $this->assertSame($name, $result->getName());
        $this->assertSame(collect($parameters)->except('driver')->toArray(), $result->getParameters());
    }

    /**
     * @expectedException \FondBot\Channels\Exceptions\ChannelNotFoundException
     * @expectedExceptionMessage Channel `fake` not found.
     */
    public function test_create_exception()
    {
        $manager = new ChannelManager();

        $manager->create('fake');
    }
}
