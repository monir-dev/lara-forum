<?php

namespace App\Events;

use Illuminate\Queue\SerializesModels;
use Illuminate\Foundation\Events\Dispatchable;

class ThreadHasNewReply
{
    use SerializesModels;

    var $thread, $reply;

    /**
     * Create a new event instance.
     *
     * @param $thread
     * @param $reply
     */
    public function __construct($thread, $reply)
    {
        $this->thread = $thread;
        $this->reply = $reply;
    }

}
