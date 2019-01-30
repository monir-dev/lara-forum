<?php

namespace App\Traits;


use App\Activity;

trait RecordActivity
{
    protected static function bootRecordActivity()
    {
        if (auth()->guest()) return;

        foreach (static::getActivitiesToRecord() as $event) {
            static::$event(function ($model) use ($event) {
                $model->recordActivity($event);
            });
        }

        static::deleting(function ($model) {
            $model->activity()->delete();
        });
    }

    public function activity()
    {
        return $this->morphMany(Activity::class, 'subject');
    }

    protected static function getActivitiesToRecord()
    {
        // laravel Model events(Model.php) ['booting', 'booted', 'retrieved', 'saving', 'saved', 'updating', 'updated', 'creating', 'created', 'deleting', 'deleted']
        return ['created'];
    }


    protected function recordActivity($event)
    {
        $this->activity()->create([
            'type' => $this->getActivityType($event),
            'user_id' => auth()->id(),
        ]);
    }

    /**
     * @param $event
     * @return string
     * @throws \ReflectionException
     */
    protected function getActivityType($event): string
    {
        $type = strtolower((new \ReflectionClass($this))->getShortName());

        return "{$event}_{$type}";
    }
}
