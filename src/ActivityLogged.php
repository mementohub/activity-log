<?php

namespace iMemento\ActivityLog;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Collection;

trait ActivityLogged
{
    protected $enableLogging = true;

    protected static function bootActivityLogs()
    {
        static::eventsToBeRecorded()->each(function ($eventName) {
            return static::$eventName(function (Model $model) use ($eventName) {

                if (! $model->shouldLogEvent($eventName))
                    return;

                app(ActivityLog::class)
                    ->setEvent($eventName)
                    ->setResource($model)
                    ->log();

            });
        });
    }

    protected static function eventsToBeRecorded(): Collection
    {
        if (isset(static::$recordEvents))
            return collect(static::$recordEvents);

        $events = collect([
            'created',
            'updated',
            'deleted',
        ]);
        if (collect(class_uses_recursive(static::class))->contains(SoftDeletes::class)) {
            $events->push('restored');
        }
        return $events;
    }

    protected function shouldLogEvent(string $eventName): bool
    {
        if (! $this->enableLogging)
            return false;

        if (in_array('deleted_at', $this->getDirty())) {
            if ($this->getDirty()['deleted_at'] === null) {
                return false;
            }
        }

        if (in_array($eventName, static::eventsToBeRecorded()->toArray()))
            return true;
    }

    public function enableLogging()
    {
        $this->enableLogging = true;

        return $this;
    }

    public function disableLogging()
    {
        $this->enableLogging = false;

        return $this;
    }

}