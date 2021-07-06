<?php

namespace Programster\OrmGenerator;


class ObjectClassCreator extends AbstractView
{
    private $m_databaseFieldNames;
    private $m_camelCaseFieldNames;
    private $m_tableClassName;
    private $m_objectClassName;


    public function __construct(string $objectClassName, string $tableClassName, array $databaseFieldNames)
    {
        $this->m_objectClassName = $objectClassName;
        $this->m_tableClassName = $tableClassName;
        $this->m_databaseFieldNames = $databaseFieldNames;
        $this->m_camelCaseFieldNames = $this->convertFieldNamesToCamelCase(...$databaseFieldNames);
    }


    /**
     * Convert any words like hello_world to camelCase like "helloWorld"
     * @param string $inputs - any number of input strings
     * @return array - the converted strings.
     */
    private function convertFieldNamesToCamelCase(string ...$inputs) : array
    {
        $outputs = [];

        foreach ($inputs as $input)
        {
            $change1 = ucwords($input, "_");
            $change2 = lcfirst($change1);
            $outputs[] = str_replace("_", "", $change2);
        }

        return $outputs;
    }


    protected function renderContent()
    {
print '<?php' . PHP_EOL . PHP_EOL;
?>


class <?= $this->m_objectClassName; ?> extends AbstractTableRowObject
{
<?php
        foreach ($this->m_camelCaseFieldNames as $fieldName)
        {
            print '    private $m_' . $fieldName . ';' . PHP_EOL;
        }
    ?>


    protected function getAccessorFunctions() : array
    {
        return array(
<?php
            foreach ($this->m_databaseFieldNames as $index => $databaseFieldName)
            {
                $camelCaseFieldName = $this->m_camelCaseFieldNames[$index];
                print '            "' . $databaseFieldName . '" => function() { return $this->m_' . $camelCaseFieldName . '; },' . PHP_EOL;
            }?>
        );
    }

    protected function getSetFunctions() : array
    {
        return array(
<?php
            foreach ($this->m_databaseFieldNames as $index => $databaseFieldName)
            {
                $camelCaseFieldName = $this->m_camelCaseFieldNames[$index];
                print '            "' . $databaseFieldName . '" => function() { $this->m_' . $camelCaseFieldName . ' = $x; },' . PHP_EOL;
            }?>
        );
    }


    public function toArray() : array
    {
        return array(
<?php
            foreach ($this->m_databaseFieldNames as $index => $databaseFieldName)
            {
                $camelCaseFieldName = $this->m_camelCaseFieldNames[$index];
                print '            "' . $databaseFieldName . '" => $this->m_' . $camelCaseFieldName . ',' . PHP_EOL;
            }
?>
        );
    }


    public function validateInputs(array $data) : array
    {
        return $data;
    }


    protected function filterInputs(array $data) : array
    {
        return $data;
    }


    public function getTableHandler() : TableInterface
    {
        return <?= $this->m_tableClassName; ?>::getInstance();
    }


    # Accessors
<?php
    foreach ($this->m_databaseFieldNames as $index => $databaseFieldName)
    {
        $camelCaseFieldName = $this->m_camelCaseFieldNames[$index];
        print '    public function get' . ucfirst($camelCaseFieldName) . '() { return $this->m_' . $camelCaseFieldName . '; }' . PHP_EOL;
    }
?>


    # Setters
<?php
    foreach ($this->m_databaseFieldNames as $index => $databaseFieldName)
    {
        $camelCaseFieldName = $this->m_camelCaseFieldNames[$index];
        print '    public function set' . ucfirst($camelCaseFieldName) . '($x) { $this->m_' . $camelCaseFieldName . ' = $x; }' . PHP_EOL;
    }
    ?>
}


<?php
    }
}
