<?php

namespace App\Http\Controllers;

use App\Thread;

class LockThreadsController extends Controller
{
    public function store(Thread $thread)
    {
        $thread->lock();
    }
}
