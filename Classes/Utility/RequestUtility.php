<?php
namespace VerteXVaaR\T3Toolkit\Utility;

use TYPO3\CMS\Core\Core\Bootstrap;

/**
 * Class RequestUtility
 */
class RequestUtility
{
    /**
     * Returns the current request ID.
     * The same ID is used for the field request_id of the TYPO3 Logging API.
     *
     * @return string
     */
    public static function getCurrentRequestId()
    {
        return Bootstrap::getInstance()->getRequestId();
    }
}
