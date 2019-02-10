<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\DatabaseMigrations;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class LockThreadsTest extends TestCase
{
    use DatabaseMigrations;

    /** @test */
    public function non_administrator_may_not_lock_a_thread()
    {
        $this
            ->withExceptionHandling()
            ->signIn();

        $thread = create('App\Thread', ['user_id' => auth()->id()]);

        $this->post(route('lock-thread', $thread))->assertStatus(403);
        $this->assertFalse(!! $thread->fresh()->locked);
    }

    /** @test */
    public function administrator_may_lock_a_thread()
    {
        $this->signIn(create('App\User', ['role_id' => 1]));


        $thread = create('App\Thread', ['user_id' => auth()->id()]);

        $this->post(route('lock-thread', $thread));

        $this->assertTrue(!! $thread->fresh()->locked);
    }

    /** @test */
    public function once_locked_a_thread_may_not_receive_new_reply()
    {
        $this->signIn();
        $thread = create('App\Thread');

        $thread->lock();

        $this->post($thread->path() . '/replies', [
            'body' => 'this is another reply',
            'user_id' => auth()->id()
        ])->assertStatus(422);
    }
}
