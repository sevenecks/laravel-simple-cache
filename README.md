## Installation

Via Composer

```bash
composer require sevenecks/laravel-simple-cache
```

## Usage
```php
<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use SevenEcks\LaravelSimpleCache\SimpleCache;

class PageController extends Controller
{
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
}
```

## Caching CSRF Tokens in Laravel

In some cases you may have forms on your pages that need a CSRF token. If you cache a page with a CSRF token on it, the form won't work (or won't work for anyone but you). There is a solution to this that doesn't involve partial page caching.

Don't cache an actual CSRF token. Cache a placeholder and then replace it after you've either rendered your view content or pulled the already rendered content from the cache.

For example, if you have a Blade template that looks like this:

```html
<!-- CSRF Token -->
<meta name="csrf-token" content="{{ csrf_token() }}">
```

You would replace the CSRF token with some placeholder text.

```html
<!-- CSRF Token -->
<meta name="csrf-token" content="xxxxxxxxxxxxxx">
```

Next, you would update your caching code to look something like this:

```php
public function contact()
{
    $view_name = 'contact';
    // check if we have a cached view
    if (!($view_content = SimpleCache::getCached($view_name))) {
        $view_content = view($view_name);
        // now let's cache our new view
        SimpleCache::setCache($view_name, $view_content->render());
    }
    // add in the csrf_token() post render/cache
    $view_content = str_replace('xxxxxxxxxxxxxx', csrf_token(), $view_content);
    // return the view either from cache or the newly created one
    return $view_content;
}
```

## API

```php
 // prefix for the cache key to namespace it
public static $cache_key_prefix = 'simple-cache-';

// text to tag the cache with by default
public static $cache_tag = '<!-- cache -->';

/**
 * Returns cached item by key if caching is enabled. If $tag_cached_content is
 * true then the the $cache_tag will be applied to the end of the content after 
 * it is pulled from the cache.
 *
 * @param  string $cache_key
 * @return mixed false if cache is disabled or not present, string if it exists.
 */
public static function getCached(string $cache_key, bool $tag_cached_content = true)

/**
 * Concatinates the prefix plus dash with suffix to create the cache key
 * namespace.
 *
 * @param  string $prefix
 * @param  string $suffix
 * @return string
 */
public static function buildCacheKey(string $prefix, string $suffix = '')

/**
 * Sets the cached content. $minutes_to_live set to -1 will live forever.
 *
 * @param  string $cache_key
 * @param  string $content
 * @param  integer $minutes_to_live = -1
 * @return mixed
 */
public static function setCache(string $cache_key, string $content, int $minutes_to_live = -1)

/**
 * Clear the entire cache
 *
 * @return none
 */
public static function clearCache()
```

Now your application should function normally, while maintaining the benefits of caching.

## Change Log
Please see [Change Log](CHANGELOG.md) for more information.

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
