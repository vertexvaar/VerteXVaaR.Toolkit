<?php
namespace VerteXVaaR\Toolkit\Persistence;

use TYPO3\CMS\Core\Database\DatabaseConnection;

/**
 * Class Backend
 */
class Backend
{
    /**
     * @param string $fields
     * @param string $table
     * @param string $where
     * @param string $groupBy
     * @param string $orderBy
     * @param string $limit
     * @return array
     */
    public function getRows($fields, $table, $where = '1=1', $groupBy = '', $orderBy = '', $limit = '')
    {
        return (array)$this->getDatabase()->exec_SELECTgetRows($fields, $table, $where, $groupBy, $orderBy, $limit);
    }

    /**
     * @return DatabaseConnection
     * @SuppressWarnings("PHPMD.Superglobals")
     */
    protected function getDatabase()
    {
        return $GLOBALS['TYPO3_DB'];
    }
}
