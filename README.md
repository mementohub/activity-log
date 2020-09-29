# iMemento Activity Log for Laravel
[![Build Status](https://github.com/mementohub/activity-log/workflows/Testing/badge.svg)](https://github.com/mementohub/activity-log/actions)
[![Latest Stable Version](https://img.shields.io/packagist/v/imemento/activity-log)](https://packagist.org/packages/imemento/activity-log)
[![License](https://img.shields.io/packagist/l/imemento/activity-log)](https://packagist.org/packages/imemento/activity-log)
[![Total Downloads](https://img.shields.io/packagist/dt/imemento/activity-log)](https://packagist.org/packages/imemento/activity-log)

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
