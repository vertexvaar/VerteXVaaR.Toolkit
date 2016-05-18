<?php
namespace VerteXVaaR\T3Toolkit\L10n;

use TYPO3\CMS\Extbase\Utility\LocalizationUtility;
use VerteXVaaR\T3Toolkit\Utility\Extensions\ExtensionNameUtility;

/**
 * Translate with less hassle and a default value.
 * If the extension key could not get guessed, because you use not standardized namespaces,
 * then you'll have to overwrite static $translationSourceExtKey with the correct extension name.
 */
trait L10nTrait
{
    /**
     * @var string
     */
    protected static $translationSourceExtKey = '';

    /**
     * Uses self::$extensionName and the given key to translate to a label.
     * If the translation was not found, $default will be returned.
     *
     * @param string $key Identifier/key of the label in TS or xlf file
     * @param string $default The default value for the translated label
     * @param array|null $arguments
     * @return string
     */
    protected static function translate($key, $default = '', array $arguments = null)
    {
        $label = LocalizationUtility::translate($key, static::getExtensionName(), $arguments);
        return (string)(empty($label) ? $default : $label);
    }

    /**
     * Implement and return your extension name here
     *
     * @return string
     */
    protected function getExtensionName()
    {
        if ('' === self::$translationSourceExtKey) {
            self::$translationSourceExtKey = ExtensionNameUtility::guessExtensionNameFromNamespace(get_called_class());
        }
        return self::$translationSourceExtKey;
    }
}
