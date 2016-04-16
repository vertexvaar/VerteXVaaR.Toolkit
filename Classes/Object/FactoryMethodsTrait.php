<?php
namespace VerteXVaaR\Toolkit\Object;

use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Extbase\Reflection\ObjectAccess;

/**
 * Class FactoryMethodsTrait
 */
trait FactoryMethodsTrait
{

    /**
     * Factory method to create an instance of this class by a given property array
     *
     * @param array $properties
     * @return static
     */
    public static function fromArray(array $properties)
    {
        $object = GeneralUtility::makeInstance(get_called_class());
        foreach ($properties as $property => $value) {
            ObjectAccess::setProperty($object, $property, $value);
        }
        return $object;
    }

    /**
     * Batch Factory method to create multiple instances from an array of property arrays
     *
     * @param array $propertyArrays
     * @return static[]
     */
    public static function fromArrays(array $propertyArrays)
    {
        $objects = [];
        foreach ($propertyArrays as $propertyArray) {
            $objects[] = static::fromArray($propertyArray);
        }
        return $objects;
    }
}
