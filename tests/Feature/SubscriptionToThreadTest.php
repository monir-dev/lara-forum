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
        $thread = create('App\Thread');

        $this->post('');
    }
}
