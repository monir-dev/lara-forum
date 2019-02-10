<?php

namespace Tests\Feature;

use App\Activity;
use App\Thread;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class CreateThreadsTest extends TestCase
{
    use DatabaseMigrations;

    /** @test */
    public function guest_may_not_create_threads()
    {
        $this->withExceptionHandling();

        $this->get('/threads/create')
            ->assertRedirect('/login');

        $this->post('/threads')
            ->assertRedirect('/login');
    }

    /** @test */
    public function authenticated_user_must_confirm_their_email_address_before_creating_a_thread()
    {
        $this->publishThreadNonVerifiedUser()
            ->assertRedirect('/email/verify');
    }

    /** @test */
    public function an_authenticated_and_email_verified_user_can_create_new_forum_threads()
    {
        $this->signIn($this->verifiedUser());

        $thread = make('App\Thread');

        $response = $this->post('/threads', $thread->toArray());

        $this->get($response->headers->get('Location'))
            ->assertSee($thread->title)
            ->assertSee($thread->body);
    }

    /** @test */
    public function a_thread_requires_a_unique_slug()
    {
        $this->signIn($this->verifiedUser());

        $thread = create('App\Thread', ['title' => 'Foo Title', 'slug' => 'foo-title']);
        $this->assertEquals($thread->fresh()->slug, 'foo-title');

        $this->post(url('/threads'), $thread->toArray());
        $this->assertTrue(Thread::whereSlug('foo-title-2')->exists());

        $this->post(url('/threads'), $thread->toArray());
        $this->assertTrue(Thread::whereSlug('foo-title-3')->exists());
    }

    /** @test */
    public function a_thread_with_a_title_end_with_a_number_should_generate_proper_slug()
    {
        $this->signIn($this->verifiedUser());

        $thread = create('App\Thread', ['title' => 'Foo Title 24', 'slug' => 'foo-title-24']);

        $this->post(url('/threads'), $thread->toArray());
        $this->assertTrue(Thread::whereSlug('foo-title-24-2')->exists());
    }

    /** @test */
    public function a_thread_requires_a_title()
    {
        $this
            ->publishThread(['title' => null])
            ->assertSessionHasErrors('title');
    }

    /** @test */
    public function a_thread_requires_a_body()
    {
        $this
            ->publishThread(['body' => null])
            ->assertSessionHasErrors('body');
    }

    /** @test */
    public function a_thread_requires_a_valid_channel()
    {
        factory('App\Channel')->create();

        $this
            ->publishThread(['channel_id' => null])
            ->assertSessionHasErrors('channel_id');

        $this
            ->publishThread(['channel_id' => 999])
            ->assertSessionHasErrors('channel_id');
    }

    /** @test */
    public function unauthorized_user_may_not_delete_a_thread()
    {
        $this->withExceptionHandling();

        $thread = create('App\Thread');

        $this->delete($thread->path())->assertRedirect('/login');

        $this->signIn();
        $this->delete($thread->path())->assertStatus(403);
    }


    /** @test */
    public function authorized_user_can_delete_a_thread()
    {
        $this->signIn();

        $thread = create('App\Thread', ['user_id' => auth()->id()]);
        $reply = create('App\Reply', ['thread_id' => $thread->id]);

        $response = $this->json('DELETE', $thread->path());

        $response->assertStatus(204);

        $this->assertDatabaseMissing('threads', ['id' => $thread->id]);
        $this->assertDatabaseMissing('replies', ['id' => $reply->id]);

        $this->assertEquals(0, Activity::count());
    }

    public function verifiedUser()
    {
        return $user = create('App\User', ['email_verified_at' => now()]);
    }

    public function publishThread($overrides = [])
    {
        $this
            ->withExceptionHandling()
            ->signIn($this->verifiedUser());

        $thread = make('App\Thread', $overrides);

        return $this->post('/threads', $thread->toArray());
    }

    public function publishThreadNonVerifiedUser($overrides = [])
    {
        $this
            ->withExceptionHandling()
            ->signIn();

        $thread = make('App\Thread', $overrides);

        return $this->post('/threads', $thread->toArray());
    }
}
