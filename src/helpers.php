<?php

use iMemento\ActivityLog\ActivityLog;

if (! function_exists('activity')) {
    function activity()
    {
        return new ActivityLog;
    }
}
