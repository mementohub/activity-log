# iMemento Activity Log for Laravel

Custom activity logger.

## Install
```bash
composer require imemento/activity-log
```

## Use
Anywhere in the app:
```php
activity()
    ->setResource($post) //optional
    ->log("$post->name was updated.");
```
Or use the `ActivityLogged` trait on a Model:
```php
class Post extends Model
{
    use ActivityLogged;
}
```