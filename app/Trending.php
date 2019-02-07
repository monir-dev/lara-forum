<?php

namespace App;


use Illuminate\Support\Facades\Redis;

class Trending
{
    public function pull()
    {
        return array_map('json_decode', Redis::zrevrange($this->cacheKey(), 0, 4));
    }

    public function push($thread)
    {
        Redis::zincrby($this->cacheKey(), 1, json_encode([
            'title' => $thread->title,
            'path' => $thread->path()
        ]));
    }

    public function cacheKey()
    {
        return app()->environment('testing') ? 'testing_trending_thread' : 'trending_thread';
    }

    public function reset()
    {
        Redis::del($this->cacheKey());
    }
}