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
    protected $meta;

    public function __construct()
    {
        $this->resource_owner = env('APP_NAME');
        $this->user = Auth::user();
        $this->meta = [
            'url' => url()->full(),
        ];
    }

    public function setUser(Authenticatable $user)
    {
        $this->user = $user;
    }

    public function setResource($resource)
    {
        if ($resource instanceof Model) {
            $this->resource_type = $resource->getMorphClass();
            $this->resource_id = $resource->getKey();
        }
        if (is_string($resource))
            $this->resource_type = $resource;
    }

    public function addMeta(array $meta = [])
    {
        $this->meta = array_merge($this->meta, $meta);
    }

    public function log(string $description = null)
    {
        $this->buildDescription();

        $data = [
            'description' => $description ?? $this->description,
            'user_id' => $this->user->id ?? null,
            'resource_owner' => $this->resource_owner,
            'resource_type' => $this->resource_type,
            'resource_id' => $this->resource_id,
            'meta' => $this->meta,
        ];

        LogJob::dispatch($data);
    }

    protected function buildDescription()
    {
        $description = ''; //User [name] created/updated/deleted [model] with id [id].

        $this->description = $description;
    }

}