<?php
namespace VerteXVaaR\Toolkit\Utility\Extensions;

use TYPO3\CMS\Core\Utility\GeneralUtility;

/**
 * Class ExtensionNameUtility
 */
class ExtensionNameUtility
{
    /**
     * @param string $namespace
     * @return string
     */
    public static function guessExtensionNameFromNamespace($namespace)
    {
        $namespace = substr($namespace, strpos($namespace, '\\') + 1);
        return GeneralUtility::camelCaseToLowerCaseUnderscored(substr($namespace, 0, strpos($namespace, '\\')));
    }
}
