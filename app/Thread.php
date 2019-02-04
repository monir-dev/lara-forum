<?php

namespace App;

use App\Events\ThreadHasNewReply;
use App\Traits\RecordActivity;
use Illuminate\Database\Eloquent\Model;
use App\Notifications\ThreadWasUpdated;

class Thread extends Model
{
    use RecordActivity;

    protected $guarded = [];

    protected $with = ['owner', 'channel'];

    protected $appends = ['isSubscribedTo'];

    protected static function boot()
    {
        parent::boot();

        static::deleting(function ($thread) {
            $thread->replies->each(function ($reply) {
                $reply->delete();
            });
        });

    }


    /**
     * Relationship with Thread and Replies
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function replies() {
        return $this->hasMany(Reply::class);
    }

    /**
     * relationship with Thread and User
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function owner() {
        return $this->belongsTo(User::class, 'user_id');
    }

    /**
     * relationship with Thread and Channel
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
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
        $reply =  $this->replies()->create($reply);

        event(new ThreadHasNewReply($this, $reply));
//        $this->notifySubscribers($reply);

        return $reply;
    }

    public function notifySubscribers($reply)
    {
        $this->subscriptions
            ->where('user_id', '!=', $reply->user_id)
            ->each
            ->notify($reply);
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

    /**
     * Subcribe a thread
     *
     * @param null $userId
     */
    public function subscribe($userId = null)
    {
        $this->subscriptions()->create([
            'user_id' => $userId ?: auth()->id()
        ]);

        return $this;
    }

    /**
     * Unsubscribe a thread
     *
     * @param null $userId
     */
    public function unsubscribe($userId = null)
    {
        $this->subscriptions()
            ->where('user_id', $userId ?: auth()->id())
            ->delete();
    }


    /**
     * Relation ship between Thread and ThreadSubscription Table
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function subscriptions()
    {
        return $this->hasMany(ThreadSubscription::class);
    }

    /**
     * Check if authenticated user subscribed to this thread
     *
     * @param null $userId
     * @return bool
     */
    public function getIsSubscribedToAttribute() :bool
    {
        return $this->subscriptions()
            ->where('user_id', auth()->id())
            ->exists();
    }

    public function hasUpdatesFor($user = null)
    {
        if (!auth()->check()) return;

        $user = $user ?: auth()->user();

        $key = $user->visitedThreadCacheKey($this);

        return $this->updated_at > cache($key);
    }

}
