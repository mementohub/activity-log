# iMemento Activity Log for Laravel

Custom activity logger.

## Install
```bash
composer require imemento/activity-log
```

## Use
```php
activity()
->setResource($post) //optional
->log("$post->name was updated.");
```
