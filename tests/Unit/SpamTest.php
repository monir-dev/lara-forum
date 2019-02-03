<?php

namespace Tests\Unit;

use Illuminate\Foundation\Testing\DatabaseMigrations;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Inspaction\Spam;

class SpamTest extends TestCase
{
    use DatabaseMigrations;

    /** @test */
    public function it_check_for_invalid_keywords()
    {
        $spam = new Spam();

        $this->assertFalse($spam->detect("valid content"));

        $this->expectException('Exception');

        $spam->detect('yahoo customer support');
    }

    /** @test */
    public function it_check_for_any_key_being_held_down()
    {
        $spam = new Spam();

        $this->expectException('Exception');

        $spam->detect("hello world aaaaaaaaaaaaaa");
    }
}
