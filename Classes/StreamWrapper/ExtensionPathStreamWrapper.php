<?php
namespace VerteXVaaR\T3Toolkit\StreamWrapper;

use TYPO3\CMS\Core\Utility\ExtensionManagementUtility;

/**
 * Class ExtensionPathStreamWrapper
 */
class ExtensionPathStreamWrapper
{
    /**
     * @var resource
     */
    public $context = null;

    /**
     * @var resource
     */
    public $handle = null;

    /**
     * @see http://php.net/manual/de/streamwrapper.dir-opendir.php
     * Open directory handle
     *
     * @param string $path
     * @param int $options
     * @return bool
     *
     * @SuppressWarnings("PHPMD.CamelCaseMethodName")
     * @SuppressWarnings("PHPMD.UnusedFormalParameter")
     * @codingStandardsIgnoreStart
     */
    public function dir_opendir($path, $options)
    {
        // @codingStandardsIgnoreEnd
        $this->handle = opendir(self::expandPath($path), $this->context);
        return is_resource($this->context);
    }

    /**
     * @see http://php.net/manual/de/streamwrapper.dir-readdir.php
     * Read entry from directory handle
     *
     * @return string
     *
     * @SuppressWarnings("PHPMD.CamelCaseMethodName")
     * @codingStandardsIgnoreStart
     */
    public function dir_readdir()
    {
        // @codingStandardsIgnoreEnd
        return readdir($this->handle);
    }

    /**
     * @see http://php.net/manual/de/streamwrapper.dir-closedir.php
     * Close directory handle
     *
     * @return bool
     *
     * @SuppressWarnings("PHPMD.CamelCaseMethodName")
     * @codingStandardsIgnoreStart
     */
    public function dir_closedir()
    {
        // @codingStandardsIgnoreEnd
        return closedir($this->handle);
    }

    /**
     * @see http://php.net/manual/de/streamwrapper.dir-rewinddir.php
     * Rewind directory handle
     *
     * @return void
     *
     * @SuppressWarnings("PHPMD.CamelCaseMethodName")
     * @codingStandardsIgnoreStart
     */
    public function dir_rewinddir()
    {
        // @codingStandardsIgnoreEnd
        rewinddir($this->handle);
    }

    /**
     * The fourth bit in options (int 8) is always set, but not documented. So it's ignored!
     *
     * @see http://php.net/manual/de/streamwrapper.mkdir.php
     * Create a directory
     *
     * @param string $path
     * @param int $mode
     * @param int $options
     * @return bool
     */
    public function mkdir($path, $mode, $options)
    {
        /*
         * GeneralUtility::mkdir is not used, because $mode would be overwritten by it.
         * A Stream specific option to use GU instead will most likely come ;)
         */
        return mkdir(self::expandPath($path), $mode, $options & STREAM_MKDIR_RECURSIVE, $this->context);
    }

    /**
     * @see http://php.net/manual/de/streamwrapper.rename.php
     * Renames a file or directory
     *
     * @param string $oldPath
     * @param string $newPath
     * @return bool
     */
    public function rename($oldPath, $newPath)
    {
        return rename(self::expandPath($oldPath), self::expandPath($newPath), $this->context);
    }

    /**
     * Yeah, $options could contain STREAM_MKDIR_RECURSIVE... What the actual f***?
     *
     * @see http://php.net/manual/de/streamwrapper.rmdir.php
     * Removes a directory
     *
     * @param string $path
     * @param int $options
     * @return bool
     *
     * @SuppressWarnings("PHPMD.UnusedFormalParameter")
     */
    public function rmdir($path, $options)
    {
        return rmdir(self::expandPath($path), $this->context);
    }

    /**
     * I know about STREAM_REPORT_ERRORS, but i still do not feel
     * responsible for suppressing the default error just to
     * check for it and "rethrow" the error afterwards.
     *
     * @see http://php.net/manual/de/streamwrapper.stream-open.php
     * Opens file or URL
     *
     * @param string $path
     * @param string $mode
     * @param int $options
     * @param string $openedPath
     * @return bool
     *
     * @SuppressWarnings("PHPMD.CamelCaseMethodName")
     * @codingStandardsIgnoreStart
     */
    public function stream_open($path, $mode, $options, &$openedPath)
    {
        // @codingStandardsIgnoreEnd
        $absolutePath = self::expandPath($path);
        $this->handle = fopen($absolutePath, $mode);

        $success = is_resource($this->handle);

        if (STREAM_USE_PATH === ($options & STREAM_USE_PATH) && $success) {
            $openedPath = $absolutePath;
        }

        return $success;
    }

    /**
     * @see http://php.net/manual/de/streamwrapper.stream-eof.php
     * Tests for end-of-file on a file pointer
     *
     * @return bool
     *
     * @SuppressWarnings("PHPMD.CamelCaseMethodName")
     * @codingStandardsIgnoreStart
     */
    public function stream_eof()
    {
        // @codingStandardsIgnoreEnd
        return feof($this->handle);
    }

    /**
     * @see http://php.net/manual/de/streamwrapper.stream-flush.php
     * Flushes the output
     *
     * @return bool
     *
     * @SuppressWarnings("PHPMD.CamelCaseMethodName")
     * @codingStandardsIgnoreStart
     */
    public function stream_flush()
    {
        // @codingStandardsIgnoreEnd
        return fflush($this->handle);
    }

    /**
     * @see http://php.net/manual/de/streamwrapper.stream-lock.php
     * Advisory file locking
     *
     * @param int $operation
     * @return bool
     *
     * @SuppressWarnings("PHPMD.CamelCaseMethodName")
     * @codingStandardsIgnoreStart
     */
    public function stream_lock($operation)
    {
        // @codingStandardsIgnoreEnd
        return flock($this->handle, $operation);
    }

    /**
     * @see http://php.net/manual/de/streamwrapper.stream-metadata.php
     * Change stream options
     *
     * @param string $path
     * @param int $option
     * @param mixed $value
     * @return bool
     *
     * @SuppressWarnings("PHPMD.CamelCaseMethodName")
     * @codingStandardsIgnoreStart
     */
    public function stream_metadata($path, $option, $value)
    {
        // @codingStandardsIgnoreEnd
        $path = self::expandPath($path);
        if (STREAM_META_TOUCH === $option) {
            return call_user_func_array('touch', [-1 => $path] + $value);
        } elseif (STREAM_META_OWNER_NAME === $option || STREAM_META_OWNER === $option) {
            return chown($path, $value);
        } elseif (STREAM_META_GROUP_NAME === $option || STREAM_META_GROUP === $option) {
            return chgrp($path, $value);
        } elseif (STREAM_META_ACCESS === $option) {
            return chmod($path, $value);
        } else {
            return false;
        }
    }

    /**
     * @see http://php.net/manual/de/streamwrapper.stream-read.php
     * Read from stream
     *
     * @param int $length
     * @return string
     *
     * @SuppressWarnings("PHPMD.CamelCaseMethodName")
     * @codingStandardsIgnoreStart
     */
    public function stream_read($length)
    {
        // @codingStandardsIgnoreEnd
        return fread($this->handle, $length);
    }

    /**
     * @see http://php.net/manual/de/streamwrapper.stream-seek.php
     * Seeks to specific location in a stream
     *
     * @param int $offset
     * @param int $whence
     * @return bool
     *
     * @SuppressWarnings("PHPMD.CamelCaseMethodName")
     * @codingStandardsIgnoreStart
     */
    public function stream_seek($offset, $whence = SEEK_SET)
    {
        // @codingStandardsIgnoreEnd
        return fseek($this->handle, $offset, $whence);
    }

    /**
     * @see http://php.net/manual/de/streamwrapper.stream-set-option.php
     * Change stream options
     *
     * @param int $option
     * @param int $arg1
     * @param int $arg2
     * @return bool
     *
     * @SuppressWarnings("PHPMD.CamelCaseMethodName")
     * @codingStandardsIgnoreStart
     */
    public function stream_set_option($option, $arg1, $arg2)
    {
        \TYPO3\CMS\Extbase\Utility\DebuggerUtility::var_dump(func_get_args(), __CLASS__ . '@' . __LINE__, 20);
        // @codingStandardsIgnoreEnd
        if (STREAM_OPTION_BLOCKING === $option) {
            return stream_set_blocking($this->handle, $arg1);
        } elseif (STREAM_OPTION_READ_BUFFER === $option) {
            // not documented but pff, implemented anyway :P
            return stream_set_read_buffer($this->handle, $arg2);
        } elseif (STREAM_OPTION_WRITE_BUFFER === $option) {
            return stream_set_write_buffer($this->handle, $arg2);
        } elseif (STREAM_OPTION_READ_TIMEOUT === $option) {
            return stream_set_timeout($this->handle, $arg1, $arg2);
        } else {
            return false;
        }
    }

    /**
     * @see http://php.net/manual/de/streamwrapper.stream-stat.php
     * Retrieve information about a file resource
     *
     * @return array
     *
     * @SuppressWarnings("PHPMD.CamelCaseMethodName")
     * @codingStandardsIgnoreStart
     */
    public function stream_stat()
    {
        // @codingStandardsIgnoreEnd
        return fstat($this->handle);
    }

    /**
     * The docs are a lie! This method is NOT called in response
     * to fseek(), instead it is only triggered by ftell()!
     * BTW: ftell() works without this implementation.
     * WTF! Implemented anyway.
     * FFS.
     *
     * @see http://php.net/manual/de/streamwrapper.stream-tell.php
     * Retrieve the current position of a stream
     *
     * @return int
     *
     * @SuppressWarnings("PHPMD.CamelCaseMethodName")
     * @codingStandardsIgnoreStart
     */
    public function stream_tell()
    {
        // @codingStandardsIgnoreEnd
        return ftell($this->handle);
    }

    /**
     * @see http://php.net/manual/de/streamwrapper.stream-truncate.php
     * Truncate stream
     *
     * @param int $limit
     * @return bool
     *
     * @SuppressWarnings("PHPMD.CamelCaseMethodName")
     * @codingStandardsIgnoreStart
     */
    public function stream_truncate($limit)
    {
        // @codingStandardsIgnoreEnd
        return ftruncate($this->handle, $limit);
    }

    /**
     * @see http://php.net/manual/de/streamwrapper.stream-write.php
     * Write to stream
     *
     * @param string $data
     * @return int
     *
     * @SuppressWarnings("PHPMD.CamelCaseMethodName")
     * @codingStandardsIgnoreStart
     */
    public function stream_write($data)
    {
        // @codingStandardsIgnoreEnd
        return fwrite($this->handle, $data);
    }

    /**
     * @http://php.net/manual/de/streamwrapper.unlink.php
     * Delete a file
     *
     * @param string $path
     * @return bool
     */
    public function unlink($path)
    {
        return unlink(self::expandPath($path), $this->context);
    }

    /**
     * @see http://php.net/manual/de/streamwrapper.url-stat.php
     * Retrieve information about a file
     *
     * @param $path
     * @param $flags
     * @return array
     *
     * @SuppressWarnings("PHPMD.CamelCaseMethodName")
     * @codingStandardsIgnoreStart
     */
    public function url_stat($path, $flags)
    {
        // @codingStandardsIgnoreEnd
        $path = self::expandPath($path);

        $quiet = STREAM_URL_STAT_QUIET === ($flags & STREAM_URL_STAT_QUIET);

        if (STREAM_URL_STAT_LINK === ($flags & STREAM_URL_STAT_LINK)) {
            if ($quiet) {
                return @lstat($path);
            } else {
                return lstat($path);
            }
        } else {
            if ($quiet) {
                return @stat($path);
            } else {
                return stat($path);
            }
        }
    }

    /**
     * Always aware of multibyte strings!
     *
     * Replaces the "EXT://EXTKEY" part with the real path to the extension folder
     *
     * @param string $path
     * @return string
     */
    public static function expandPath($path)
    {
        // cut away "EXT://"
        $rawPath = mb_substr($path, 6);

        // split key and trailing path at first slash
        $pos = mb_strpos($rawPath, '/') ?: null;
        if (null === $pos) {
            $key = mb_substr($rawPath, 0);
            $rest  = '';
        } else {
            $key = mb_substr($rawPath, 0, $pos);
            $rest = mb_substr($rawPath, $pos + 1);
        }

        return ExtensionManagementUtility::extPath($key, $rest);
    }
}
