<?php

declare(strict_types=1);

namespace Tests\Unit\Conversation;

use FondBot\Bot;
use Tests\TestCase;
use FondBot\Conversation\Context;
use FondBot\Contracts\Channels\User;
use Tests\Classes\Fakes\FakeInteraction;
use FondBot\Contracts\Channels\ReceivedMessage;

/**
 * @property mixed|\Mockery\Mock                  bot
 * @property mixed|\Mockery\Mock                  context
 * @property \Tests\Classes\Fakes\FakeInteraction interaction
 */
class InteractionTest extends TestCase
{
    protected function setUp()
    {
        parent::setUp();

        $this->bot = $this->mock(Bot::class);
        $this->context = $this->mock(Context::class);

        $this->bot->shouldReceive('getContext')->andReturn($this->context);

        $this->interaction = new FakeInteraction;
    }

    public function test_run_current_interaction_in_context_and_do_not_run_another_interaction()
    {
        $message = $this->mock(ReceivedMessage::class);

        $this->context->shouldReceive('getInteraction')->andReturn($this->interaction)->once();
        $this->context->shouldReceive('getMessage')->andReturn($message)->once();
        $this->context->shouldReceive('setValue')->with('key', 'value')->once();
        $this->bot->shouldReceive('clearContext')->once();

        $this->interaction->handle($this->bot);
    }

    public function test_run_current_interaction_not_in_context()
    {
        $sender = $this->mock(User::class);

        $this->context->shouldReceive('getUser')->andReturn($sender)->once();
        $this->context->shouldReceive('getInteraction')->andReturnNull()->once();
        $this->context->shouldReceive('setInteraction')->with($this->interaction)->once();
        $this->bot->shouldReceive('sendMessage')
            ->with($sender, $this->interaction->text(), $this->interaction->keyboard())
            ->once();

        $this->interaction->handle($this->bot);
    }
}
