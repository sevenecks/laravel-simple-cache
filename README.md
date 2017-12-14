## Installation

Via Composer

```bash
composer require sevenecks/laravel-simple-cache
```

## Usage
```php
/**
 * Gets the contact page from cache or renders it and saves to cache 
 * before returning
 *
 * @return string view
 */
public function contact()
{
    $view_name = 'contact';
    // check if we have a cached view
    if (!($view_content = SimpleCache::getCached($view_name))) {
        $view_content = view($view_name);
        // now let's cache our new view
        SimpleCache::setCache($view_name, $view_content->render());
    }
    // return the view either from cache or the newly created one
    return $view_content;
}
```

## Change Log
Please see [Change Log](CHANGELOG.md) for more information.

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
