<?php

namespace App\Traits;


use App\Favorite;

trait Favoritable
{

    protected static function bootFavoritable()
    {
        static::deleting(function ($model) {
            $model->favorites->each->delete();
        });
    }


    /**
     * @return mixed
     */
    public function favorites()
    {
        return $this->morphMany(Favorite::class, 'favorited');
    }

    /**
     * @return mixed
     */
    public function favorite()
    {
        $attributes = ['user_id' => auth()->id()];

        if (!$this->favorites()->where($attributes)->exists()) {
            return $this->favorites()->create($attributes);
        }
    }

    /**
     * @return mixed
     */
    public function unfavorite()
    {
        $attributes = ['user_id' => auth()->id()];

        if ($this->favorites()->where($attributes)->exists()) {
            return $this->favorites()->where($attributes)->get()->each(function ($favorite) {
                $favorite->delete();
            });
        }
    }


    public function isFavorited()
    {
        return !! $this->favorites->where('user_id', auth()->id())->count();
    }


    public function getIsFavoritedAttribute()
    {
        return $this->isFavorited();
    }

    /**
     * @return mixed
     */
    public function getFavoritesCountAttribute()
    {
        return $this->favorites->count();
    }
}
