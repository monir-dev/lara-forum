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

    /**
     * Return thread path
     *
     * @return string
     */
    public function path() {
        return "/threads/{$this->channel->slug}/{$this->id}";
    }

    /**
     * Add a reply to the thread
     *
     * @param $reply
     * @return Model
     */
    public function addReply($reply)
    {
        return $this->replies()->create($reply);
    }

    /**
     * Apply all relevent thred filter
     *
     * @param $query
     * @param $filters
     * @return mixed
     */
    public function scopeFilter($query, $filters)
    {
        return $filters->apply($query);
    }

    public function subscribe($userId = null)
    {
        $this->subscriptions()->create([
            'user_id' => $userId ?: auth()->id()
        ]);
    }

     public function unsubscribe($userId = null)
    {
        $this->subscriptions()
            ->where('user_id', $userId ?: auth()->id())
            ->delete();
    }



    public function subscriptions()
    {
        return $this->hasMany(ThreadSubscription::class);
    }


}
