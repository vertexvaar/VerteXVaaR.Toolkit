<?php
namespace VerteXVaaR\Toolkit\Controller;

use TYPO3\CMS\Core\Messaging\AbstractMessage;
use TYPO3\CMS\Extbase\Mvc\Controller\ActionController;
use TYPO3\CMS\Extbase\Property\TypeConverter\DateTimeConverter;
use VerteXVaaR\Toolkit\Log\LogTrait;

/**
 * Class AdvancedActionController
 */
class AdvancedActionController extends ActionController
{
    use LogTrait;

    /**
     * Sets defaults for an argument, if no argument values were not submitted by the user
     *
     * @param string $name Name of the argument
     * @param mixed $value The default value(s)
     */
    protected function setDefaultsForArgument($name, $value)
    {
        if (false === $this->request->hasArgument($name)) {
            if (is_array($value)) {
                $mappingConfiguration = $this->arguments->getArgument($name)->getPropertyMappingConfiguration();
                foreach (array_keys($value) as $propertyName) {
                    $mappingConfiguration->allowProperties($propertyName);
                }
            }
            $this->request->setArgument($name, $value);
        }
    }

    /**
     * Use in your initialize*Action to correctly convert user input into DateTime objects
     * No need for an additional ->setTypeConverter() call!
     *
     * @param string $name Name of the argument
     * @param string $index Name of the arguments property (if result is an object) or index (if result is an array)
     * @param string $format Format of the input
     * @throws \TYPO3\CMS\Extbase\Mvc\Exception\NoSuchArgumentException
     */
    protected function setDateTimeConverterFormat($name, $index, $format)
    {
        $this->arguments->getArgument($name)
                        ->getPropertyMappingConfiguration()
                        ->forProperty($index)
                        ->setTypeConverterOption(
                            DateTimeConverter::class,
                            DateTimeConverter::CONFIGURATION_DATE_FORMAT,
                            $format
                        );
    }

    /**
     * Checks and builds the controllerContext if necessary, so this method can be used in initialize methods
     *
     * @param string $message
     * @param string $title
     * @param int $severity
     * @param bool $storeInSession
     */
    public function addFlashMessage($message, $title = '', $severity = AbstractMessage::OK, $storeInSession = true)
    {
        if (null === $this->controllerContext) {
            $this->controllerContext = $this->buildControllerContext();
        }
        parent::addFlashMessage($message, $title, $severity, $storeInSession);
    }
}
