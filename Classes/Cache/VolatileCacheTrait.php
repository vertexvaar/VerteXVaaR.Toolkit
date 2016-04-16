<?php
namespace VerteXVaaR\Toolkit\Cache;

/**
 * Class VolatileCacheTrait
 */
trait VolatileCacheTrait
{
    /**
     * @var array
     */
    private static $runtimeCache = [];

    /**
     * @param string $key
     * @param mixed $value
     */
    protected static function setCache($key, $value)
    {
        self::$runtimeCache[$key] = $value;
    }

    /**
     * @param string $key
     * @param mixed $default Value to return (and write to the cache) if the key was not set, yet.
     * @return mixed
     */
    protected static function getCache($key, $default)
    {
        if (!static::hasCache($key)) {
            static::setCache($key, $default);
        }
        return self::$runtimeCache[$key];
    }

    /**
     * @param string $key
     * @return bool
     */
    protected static function hasCache($key)
    {
        return array_key_exists($key, self::$runtimeCache);
    }
}
