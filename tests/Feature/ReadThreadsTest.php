<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\DatabaseMigrations;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ReadThreadsTest extends TestCase
{
    use DatabaseMigrations;

    public function setUp()
    {
        parent::setUp();

        $this->thread = factory('App\Thread')->create();
    }

    /** @test */
    public function a_user_can_view_all_threads()
    {
        $this->get('/threads')
            ->assertSee($this->thread->title);
    }

    /** @test */
    function a_user_can_read_a_single_thread()
    {
        $this->get($this->thread->path())
            ->assertSee($this->thread->title);
    }

    /** @test */
    function a_user_can_read_replies_that_are_associated_with_a_thread()
    {
        $reply = factory('App\Reply')->create(['thread_id' => $this->thread->id]);

        $this->assertDatabaseHas('replies', ['body'=> $reply->body]);
    }

    /** @test */
    public function a_user_can_filter_thread_according_to_tags()
    {
        $channel = create('App\Channel');
        $threadInChannel = create('App\Thread', ['channel_id' => $channel->id]);
        $threadNotInChannel = create('App\Thread');

        $this
            ->get('/threads/' . $channel->slug)
            ->assertSee($threadInChannel->title)
            ->assertDontSee($threadNotInChannel->title);
    }

    /** @test */
    public function a_user_can_filter_thread_by_any_username()
    {
        $this->signIn(create('App\User', ['name' => 'JhonDoe']));

        $threadByJhon = create('App\Thread', ['user_id' => auth()->id()]);
        $threadNotByJhon = create('App\Thread');

        $this
            ->get('/threads?by=JhonDoe')
            ->assertSee($threadByJhon->title)
            ->assertDontSee($threadNotByJhon->title);
    }

    /** @test */
    public function a_user_can_filter_thread_by_popularity()
    {
        $threadWithThreeReplies = create('App\Thread');
        create('App\Reply', ['thread_id' => $threadWithThreeReplies->id]);
        create('App\Reply', ['thread_id' => $threadWithThreeReplies->id]);
        create('App\Reply', ['thread_id' => $threadWithThreeReplies->id]);

        $threadWithTwoReplies = create('App\Thread');
        create('App\Reply', ['thread_id' => $threadWithTwoReplies->id]);
        create('App\Reply', ['thread_id' => $threadWithTwoReplies->id]);

        $threadWithNoReplies = $this->thread;

        $response = $this->getJson('/threads?popular=1')->json();

        $this->assertEquals([3,2,0], array_column($response['data'], 'replies_count'));
    }

    /** @test */
    public function a_user_can_filter_thread_by_those_are_unanswered()
    {
        $threa = create('App\Thread');
        create('App\Reply', ['thread_id' => $threa->id]);

        $response = $this->getJson('/threads?unanswered=1')->json();

        $this->assertCount(1, $response['data']);
    }

    /** @test */
    public function a_user_can_request_for_all_reply_of_a_given_thread()
    {
        $thread = create('App\Thread');

        create('App\Reply', ['thread_id' => $thread->id]);

        $response = $this->getJson($thread->path() . '/replies')->json();

        $this->assertCount(1, $response["data"]);
    }

    /** @test */
    public function we_record_a_new_visit_each_time_a_thread_is_read()
    {
        $thread = create('App\Thread');

        $this->assertSame(0, (int) $thread->visits_count);

        $this->call('GET', $thread->path());
        $this->assertSame(1, (int) $thread->fresh()->visits_count);
    }

}
