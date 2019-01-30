<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\DatabaseMigrations;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class FavoritesTest extends TestCase
{
    use DatabaseMigrations;

    /** @test */
    public function guest_can_not_favorite_anything()
    {
        $this
            ->withExceptionHandling()
            ->post('/replies/1/favorites')
            ->assertRedirect('/login');
    }

    /** @test */
    public function an_authenticated_user_can_favorite_any_reply()
    {
        $this->signIn();

        $reply = create('App\Reply'); // this will also create a thread

        $this->post('/replies/' . $reply->id . '/favorites');

        $this->assertCount(1, $reply->favorites);
    }

    /** @test */
    public function an_authenticated_user_can_unfavorite_any_reply()
    {
        $this->signIn();

        $reply = create('App\Reply'); // this will also create a thread

        $reply->favorite();

        $this->delete('/replies/' . $reply->id . '/favorites');
        $this->assertCount(0, $reply->favorites);
    }

    /** @test */
    public function an_authenticated_user_may_favorite_a_reply_once()
    {
        $this->signIn();

        $reply = create('App\Reply'); // this will also create a thread

        try {
            $this->post('/replies/' . $reply->id . '/favorites');
            $this->post('/replies/' . $reply->id . '/favorites');
        } catch (\Exception $e) {
            $this->fail("Didn't expect to set same record twice");
        }

        $this->assertCount(1, $reply->favorites);
    }
}
