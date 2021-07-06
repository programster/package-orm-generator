<?php

namespace Programster\OrmGenerator;

class TableClassCreator extends AbstractView
{
    private $m_tableName;
    private $m_tableClassName;
    private $m_objectClassName;


    public function __construct(string $tableClassName, string $objectClassName, string $tableName)
    {
        $this->m_tableName = $tableName;
        $this->m_objectClassName = $objectClassName;
        $this->m_tableClassName = $tableClassName;
    }


    protected function renderContent()
    {
        print '<?php' . PHP_EOL . PHP_EOL;
?>


class <?= $this->m_tableClassName; ?> extends AbstractTable
{
    public function getObjectClassName() : string
    {
        return __NAMESPACE__ . '\<?= $this->m_objectClassName; ?>';
    }


    public function getTableName() : string
    {
        return '<?= $this->m_tableName ?>';
    }


    public function validateInputs(array $data) : array
    {
        return $data;
    }


    public function getFieldsThatAllowNull() : array
    {
        return array();
    }


    public function getFieldsThatHaveDefaults() : array
    {
        return array();
    }
}





<?php
    }

}
