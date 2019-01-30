<?php

namespace App;

use App\Traits\RecordActivity;
use Illuminate\Database\Eloquent\Model;

class Thread extends Model
{
    use RecordActivity;

    protected $guarded = [];

    protected $with = ['owner', 'channel'];

    protected static function boot()
    {
        parent::boot();

        static::addGlobalScope('replyCount', function ($builder) {
            $builder->withCount('replies');
        });

        static::deleting(function ($thread) {
            $thread->replies->each(function ($reply) {
                $reply->delete();
            });
        });

    }


    public function replies() {
        return $this->hasMany(Reply::class);
    }

    public function owner() {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function channel() {
        return $this->belongsTo(Channel::class);
    }

    public function path() {
        return "/threads/{$this->channel->slug}/{$this->id}";
    }

    public function addReply($reply)
    {
        $this->replies()->create($reply);
    }

    public function scopeFilter($query, $filters)
    {
        return $filters->apply($query);
    }


}
