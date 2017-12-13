<?php

namespace SevenEcks\LaravelSimpleCache;

use Illuminate\Support\Facades\Cache;
use Config;

class SimpleCache
{
     // prefix for the cache key to namespace it
    public static $cache_key_prefix = 'simple-cache-';
    // text to tag the cache with by default
    public static $cache_tag = '<!-- cache -->';

    /**
     * Returns cached item by key if caching is enabled
     * @param  string $cache_key
     * @return mixed false if cache is disabled or not present, string if it exists.
     */
    public static function getCached(string $cache_key, bool $tag_cached_content = true)
    {
        $cache_key = self::buildCacheKey($this->cache_key_prefix, $cache_key);
        if (Config::get('app.cacheDisabled') || !Cache::has($cache_key)) {
            return false;
        }
        return Cache::get($cache_key) . ($tag_cached_content ? self::$cache_tag : '');
    }

    /**
     * Concatinates the prefix plus dash with suffix to create the cache key
     * namespace
     *
     * @param  string $prefix
     * @param  string $suffix
     * @return string
     */
    public static function buildCacheKey(string $prefix, string $suffix = '')
    {
        return $prefix . "-" . $suffix;
    }

    /**
     * Sets the cached content
     *
     * @param string $cache_key
     * @param string $content
     * @param integer $minutes_to_live = -1
     * @return mixed
     */
    public static function setCache(string $cache_key, string $content, int $minutes_to_live = -1)
    {
        $cache_key = self::buildCacheKey($this->cache_key_prefix, $cache_key);
        if ($minutes_to_live == -1) {
            return Cache::forever($cache_key, $content);
        } else {
            return Cache::put($cache_key, $content, $minutes_to_live);
        }
    }
}
