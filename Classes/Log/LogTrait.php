<?php
namespace VerteXVaaR\Toolkit\Log;

use TYPO3\CMS\Core\Log\Logger;
use TYPO3\CMS\Core\Log\LogManager;
use TYPO3\CMS\Core\Utility\GeneralUtility;

/**
 * Usage: add to your class with the use statement.
 * Anytime you want to log call: self::getLogger() and the desired ->log() method
 * Alternatively you can use $this->log() in non-static context
 */
trait LogTrait
{
    /**
     * @var null
     */
    private static $logger = null;

    /**
     * @return Logger
     */
    protected static function getLogger()
    {
        if (null === self::$logger) {
            self::$logger = GeneralUtility::makeInstance(LogManager::class)->getLogger(get_called_class());
        }
        return self::$logger;
    }

    /**
     * @param int|string $level Log level. Value according to \TYPO3\CMS\Core\Log\LogLevel.
     * @param string $message Log message.
     * @param array $data Optional array of data to log
     */
    protected function log($level, $message, array $data = array())
    {
        self::getLogger()->log($level, $message, $data);
    }
}
