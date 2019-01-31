<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\DatabaseMigrations;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ParticipateInForumTest extends TestCase
{
    use DatabaseMigrations;

    /** @test */
    public function unauthenticated_user_may_not_add_replies()
    {
        $this
            ->withExceptionHandling()
            ->post('threads/some-channel/1/replies',[])
        ->assertRedirect('/login');
    }

    /** @test */
    public function an_authenticated_user_may_participate_in_forum_threads()
    {
        $this->signIn();

        $thread = create('App\Thread');
        $reply = make('App\Reply');

        $this->post($thread->path().'/replies', $reply->toArray());

        $this->assertDatabaseHas('replies', ['body' => $reply->body]);
        $this->assertEquals(1, $thread->fresh()->replies_count);
    }

    /** @test */
    public function a_reply_requires_a_body()
    {
        $this->withExceptionHandling()->signIn();

        $thread = create('App\Thread');
        $reply = make('App\Reply', ['body' => null]);

        $this->post($thread->path().'/replies', $reply->toArray())
            ->assertSessionHasErrors('body');
    }

    /** @test */
    public function unauthrized_user_can_not_delete_a_reply()
    {
        $this->withExceptionHandling();

        $reply = create('App\Reply');

        $this->delete("/replies/{$reply->id}")
            ->assertStatus(302);

        $this->signIn()
            ->delete("/replies/{$reply->id}")
            ->assertStatus(403);
    }

    /** @test */
    public function authrized_user_can_delete_a_reply()
    {
        $this->signIn();

        $reply = create('App\Reply', ['user_id' => auth()->id()]);

        $this->delete("/replies/{$reply->id}");
        $this->assertDatabaseMissing('replies', ['id' => $reply->id]);
        $this->assertEquals(0, $reply->thread->fresh()->replies_count);
    }

    /** @test */
    public function unauthrized_user_can_not_update_a_reply()
    {
        $this->withExceptionHandling();

        $reply = create('App\Reply');

        $changedBody = 'Update yourself';
        $this->patch("/replies/{$reply->id}", ['body' => $changedBody])
            ->assertRedirect('login');

        $this->signIn()
            ->patch("/replies/{$reply->id}", ['body' => $changedBody])
            ->assertStatus(403);
    }

    /** @test */
    public function authrized_user_can_update_a_reply()
    {
        $this->signIn();

        $reply = create('App\Reply', ['user_id' => auth()->id()]);

        $changedBody = 'Update yourself';
        $this->patch("/replies/{$reply->id}", ['body' => $changedBody]);

        $this->assertDatabaseHas('replies', ['id' => $reply->id, 'body' => $changedBody]);
    }
}
