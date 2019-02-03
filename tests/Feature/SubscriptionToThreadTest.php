<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\DatabaseMigrations;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class SubscriptionToThreadTest extends TestCase
{
    use DatabaseMigrations;

    /** @test  */
    public function a_user_can_subscribe_to_a_thread()
    {
        $this->signIn();

        // given we have a thread
        $thread = create('App\Thread');

        // and user subscribe to that thread
        $this->post($thread->path() . '/subscriptions');

        $this->assertCount(1, $thread->subscriptions);
    }

    /** @test  */
    public function a_user_can_unsubscribe_to_a_thread()
    {
        $this->signIn();

        // given we have a thread
        $thread = create('App\Thread');

        $thread->subscribe();

        // and user unsubscribe to that thread
        $this->delete($thread->path() . '/subscriptions');

        // a notification should be prepared for the user
        $this->assertCount(0, $thread->subscriptions);
    }

    /** @test */
    public function it_knows_if_the_authenticated_user_subscribed_to_a_thread()
    {
        $this->signIn();

        // given we have a thread
        $thread = create('App\Thread');

        $thread->subscribe();

        $this->assertTrue($thread->isSubscribedTo);
    }
}
