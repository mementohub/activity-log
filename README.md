# iMemento Activity Log for Laravel
[![Build Status](https://travis-ci.org/mementohub/activity-log.svg?branch=master)](https://travis-ci.org/mementohub/activity-log)
[![Latest Stable Version](https://poser.pugx.org/imemento/activity-log/v/stable)](https://packagist.org/packages/imemento/activity-log)
[![License](https://poser.pugx.org/imemento/activity-log/license)](https://packagist.org/packages/imemento/activity-log)
[![Total Downloads](https://poser.pugx.org/imemento/activity-log/downloads)](https://packagist.org/packages/imemento/activity-log)

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
