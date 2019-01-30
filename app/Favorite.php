<?php

namespace App;

use App\Traits\RecordActivity;
use Illuminate\Database\Eloquent\Model;

class Favorite extends Model
{
    use RecordActivity;

    protected $guarded = [];

    public function favorited()
    {
        return $this->morphTo();
    }
}
