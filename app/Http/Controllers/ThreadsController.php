<?php

namespace App\Http\Controllers;

use App\Channel;
use App\Rules\SpamFree;
use App\Thread;
use App\Filters\ThreadFilters;
use App\Trending;
use App\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Redis;

/**
 * Class ThreadsController
 * @package App\Http\Controllers
 */
class ThreadsController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth')->except(['index', 'show']);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Channel $channel, ThreadFilters $filters, Trending $trending)
    {
        $threads = $this->getThreads($channel, $filters);

        if (request()->wantsJson()) {
            return $threads;
        }

        return view('threads.index', [
            'threads' => $threads,
            'trending' => $trending->pull()
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('threads.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request)
    {
        $this->validate($request, [
            'title' => ['required', new SpamFree()],
            'body' => ['required', new SpamFree()],
            'channel_id' => 'required|exists:channels,id'
        ]);


        $thread = Thread::create([
           'user_id' => auth()->id(),
           'channel_id' => request('channel_id'),
           'title' => request('title'),
           'body' => request('body'),
           'slug' => request('title')
        ]);

        return redirect($thread->path())
            ->with('flash', 'Your thread has beed published');
    }

    /**
     * Display the specified resource.
     *
     * @param  $channelId
     * @param  \App\Thread  $thread
     * @return \Illuminate\Http\Response
     */
    public function show($channelId,Thread $thread, Trending $trending)
    {
        if (auth()->check()) {
            auth()->user()->read($thread);
        }

        $trending->push($thread);

        # for Redis visit count
//        $thread->visits()->count();

        # for table visits_count column increment
        $thread->increment('visits_count');

        return view('threads.show', [
            'thread' => $thread
        ]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Thread  $thread
     * @return \Illuminate\Http\Response
     */
    public function destroy($channel, Thread $thread)
    {
        $this->authorize('delete', $thread);

        $thread->delete();

        if (request()->wantsJson()) {
            return response([], 204);
        }

        return redirect('/threads');
    }

    /**
     * @param Channel $channel
     * @param ThreadFilters $filters
     * @return mixed
     */
    protected function getThreads(Channel $channel, ThreadFilters $filters)
    {
        $threads = Thread::latest()->filter($filters);

        if ($channel->exists) {
            $threads->where('channel_id', $channel->id);
        }

        return $threads->paginate(25);
    }
}
