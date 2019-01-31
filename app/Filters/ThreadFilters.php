<?php

namespace App\Filters;

use App\User;
use Illuminate\Http\Request;

class ThreadFilters extends Filters
{
    protected $filters = ['by', 'popular', 'unanswered'];

    /**
     * Filter a query by a given username
     *
     * @param string $username
     * @return mixed
     */
    protected function by($username)
    {
        $user = User::where('name', $username)->firstOrFail();

        return $this->builder->where('user_id', $user->id);
    }

    /**
     * Filter a query by popularity
     *
     * @return mixed
     */
    protected function popular()
    {
        $this->builder->getQuery()->orders = [];

        return $this->builder->orderBy('replies_count', 'desc');
    }

    /**
     * Filter a query by unanswered
     *
     * @return mixed
     */
    protected function unanswered()
    {
        return $this->builder->where('replies_count', 0);
    }
}
