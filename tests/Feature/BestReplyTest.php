<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\DatabaseMigrations;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class BestReplyTest extends TestCase
{
    use DatabaseMigrations;

    /** @test */
    public function a_thread_creator_can_mark_any_thread_as_best_reply()
    {
        $this->signIn();
        $thread = create('App\Thread', ['user_id' => auth()->id()]);

        create('App\Reply', ['thread_id' => $thread->id]);
        $secondReply = create('App\Reply', ['thread_id' => $thread->id]);

        $this->assertFalse($secondReply->isBest());

        $this->postJson("/replies/{$secondReply->id}/best");
        $this->assertTrue($secondReply->fresh()->isBest());
    }

    /** @test */
    public function only_a_thread_owner_can_mark_a_reply_as_best()
    {
        $this->withExceptionHandling();
        $this->signIn();
        $thread = create('App\Thread', ['user_id' => auth()->id()]);

        create('App\Reply', ['thread_id' => $thread->id]);
        $secondReply = create('App\Reply', ['thread_id' => $thread->id]);

        $this->signIn(create('App\User'));

        $this->postJson("/replies/{$secondReply->id}/best")->assertStatus(403);
        $this->assertFalse($secondReply->fresh()->isBest());
    }

    /** @test */
    public function if_a_best_reply_is_deleted_then_the_thread_should_properly_update_to_reflect_that()
    {
        $this->signIn();

        $reply = create('App\Reply', ['user_id' => auth()->id()]);

        $reply->thread->markBestReply($reply);

        $this->deleteJson("/replies/{$reply->id}");

        $this->assertNull($reply->thread->fresh()->best_reply_id);
    }
}
