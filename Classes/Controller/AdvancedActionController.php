<?php
namespace VerteXVaaR\Toolkit\Controller;

use TYPO3\CMS\Extbase\Mvc\Controller\ActionController;
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
}
