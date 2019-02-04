<?php

namespace App\Listeners;

use App\Events\ThreadHasNewReply;
use App\Notifications\YouWereMentioned;
use App\User;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class NotifiyMentionedUsers
{
    /**
     * Handle the event.
     *
     * @param  ThreadHasNewReply  $event
     * @return void
     */
    public function handle(ThreadHasNewReply $event)
    {
        // find all mentioned user in reply body, then notify them
        collect($event->reply->mentionedUsers())
            ->map(function($username) {
                return User::whereName($username)->first();
            })
            ->filter()
            ->each(function($user) use ($event) {
                $user->notify(new YouWereMentioned($event->reply));
            });
    }
}
