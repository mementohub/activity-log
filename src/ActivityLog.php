<?php

namespace iMemento\ActivityLog;

use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class ActivityLog
{
    protected $description;
    protected $user;
    protected $resource_owner;
    protected $resource_type;
    protected $resource_id;
    protected $resource;
    protected $event;
    protected $meta;
    protected $include_changes;

    public function __construct()
    {
        $this->resource_owner = env('APP_NAME');
        $this->user = Auth::user();
        $this->meta = [
            'url' => url()->full(),
        ];

        $this->includeChanges();
    }

    public function setUser(Authenticatable $user)
    {
        $this->user = $user;

        return $this;
    }

    public function setEvent(string $event)
    {
        $this->event = $event;

        return $this;
    }

    public function setResource($resource)
    {
        if ($resource instanceof Model) {
            $this->resource_type = $resource->getMorphClass();
            $this->resource_id = $resource->getKey();
            $this->resource = $resource;
        }
        if (is_string($resource))
            $this->resource_type = $resource;

        return $this;
    }

    public function addMeta(array $meta = [])
    {
        $this->meta = array_merge($this->meta, $meta);

        return $this;
    }

    public function log(string $description = null)
    {
        $this->addChanges();
        $this->buildDescription();

        $data = [
            'description' => $description ?? $this->description,
            'user_id' => $this->user->id ?? null,
            'resource_owner' => $this->resource_owner,
            'resource_type' => $this->resource_type,
            'resource_id' => $this->resource_id,
            'event' => $this->event,
            'meta' => $this->meta,
        ];

        LogJob::dispatch($data);
    }

    protected function buildDescription()
    {
        $user_text = empty($this->user->id) ? "" : "by user with id {$this->user->id}";
        $event_text = $this->event ? $this->event : "touched";

        if (isset($this->resource)) {
            $this->description = "$this->resource_type with id $this->resource_id on service $this->resource_owner was $event_text $user_text";
        } else {
            $this->description = "Activity logged";
        }

        return $this;
    }

    /*
     * Adds the modified data and makes sure to decode the json fields for proper storage...
     * */
    protected function addChanges()
    {
        $casts = collect($this->resource->getCasts())->filter(function($value) {
            return $value === 'array';
        })->keys();

        $changes = $this->resource->getChanges();

        foreach ($changes as $k => $v) {
            if ($casts->contains($k))
                $changes[$k] = json_decode($v, true);
        }

        if ($this->resource && $this->include_changes && ! empty($changes))
            $this->meta = array_merge($this->meta, [
                'changes' => $changes,
            ]);

        return $this;
    }

    public function includeChanges()
    {
        $this->include_changes = true;

        return $this;
    }

    public function excludeChanges()
    {
        $this->include_changes = false;

        return $this;
    }

}