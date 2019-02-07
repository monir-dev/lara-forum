<?php

namespace App;


use Illuminate\Support\Facades\Redis;

class Visit
{
    protected $thread;

    public function __construct($thread)
    {
        $this->thread = $thread;
    }

    public function reset()
    {
        Redis::del($this->cacheKey());

        return $this;
    }

    public function count()
    {
        return Redis::get($this->cacheKey()) ?? 0;
    }

    public function record()
    {
        Redis::incr($this->cacheKey());

        return $this;
    }

    public function cacheKey()
    {
        return "thread.{$this->thread->id}.visits";
    }
}