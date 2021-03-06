<?php

namespace Tests\Unit;

use Carbon\Carbon;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ReplyTest extends TestCase
{
    use DatabaseMigrations;

    /** @test */
    public function a_reply_has_an_owner() {
        $reply = factory('App\Reply')->create();
        $this->assertInstanceOf('App\User', $reply->owner);
    }

    /** @test */
    public function it_knows_if_it_is_just_published() {
        $reply = create('App\Reply');

        $this->assertTrue($reply->wasJustPublished());

        $reply->created_at = Carbon::now()->subMonth();

        $this->assertFalse($reply->wasJustPublished());
    }

    /** @test */
    public function it_can_detect_all_mentioned_user_in_the_body() {
        $reply = create('App\Reply', [
            'body' => '@JohnDoe is it you, @JaneDoe'
        ]);

        $this->assertEquals(['JohnDoe', 'JaneDoe'], $reply->mentionedUsers());
    }

    /** @test */
    public function it_wraps_mentioned_usernames_within_anchor_tags() {
        $reply = new \App\Reply([
            'body' => 'Hello, @JohnDoe.'
        ]);

        $this->assertEquals(
            'Hello, <a href="/profiles/JohnDoe">@JohnDoe</a>.',
            $reply->body
        );
    }

    /** @test */
    public function it_knows_if_it_is_best()
    {
        $reply = create('App\Reply');

        $this->assertFalse($reply->isBest());

        $reply->thread->update(['best_reply_id' => $reply->id]);
        $this->assertTrue($reply->fresh()->isBest());
    }
}
