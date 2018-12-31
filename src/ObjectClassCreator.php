<?php

namespace Programster\OrmGenerator;


class ObjectClassCreator extends AbstractView
{
    private $m_fields;
    private $m_tableClassName;
    private $m_objectClassName;
    
    
    public function __construct(string $objectClassName, string $tableClassName, array $fieldNames)
    {
        $this->m_objectClassName = $objectClassName;
        $this->m_tableClassName = $tableClassName;
        $this->m_fields = $fieldNames;
    }
    
    
    protected function renderContent()
    {
print '<?php' . PHP_EOL . PHP_EOL;
?>


class <?= $this->m_objectClassName; ?> extends \iRAP\MysqlObjects\AbstractResourceObject
{
<?php
        foreach ($this->m_fields as $fieldName)
        {
            print '    private $m_' . $fieldName . ';' . PHP_EOL;
        }
    ?>
    
    
    public function __construct($row, $row_field_types=null) 
    {
        $this->initializeFromArray($row, $row_field_types);
    }
    
    
    protected function getAccessorFunctions() 
    {
        return array(
<?php
            foreach ($this->m_fields as $fieldName)
            {
                print '            "' . $fieldName . '" => function() { return $this->m_' . $fieldName . '; },' . PHP_EOL;
            }?>
        );
    }
    
    protected function getSetFunctions() 
    {
        return array(
<?php
            foreach($this->m_fields as $fieldName)
            {
                print '            "' . $fieldName . '" => function() { $this->m_' . $fieldName . ' = $x; },' . PHP_EOL;
            }?>
        );
    }
    
    
    public function getPublicArray() 
    {
        return array(
<?php
            foreach($this->m_fields as $fieldName)
            {
                print '            "' . $fieldName . '" => $this->m_' . $fieldName . ',' . PHP_EOL;
            }
?>
        );
    }
    
    
    /**
     * 
     * @param array $data
     */
    protected static function filter_inputs(array $data) 
    {
        return $data;
    }
    
    public function validateInputs(array $data) 
    { 
        return $data; 
    }
    
    
    protected function filterInputs(array $data) 
    { 
        return $data; 
    }
    
    
    public function getTableHandler() 
    { 
        return <?= $this->m_tableClassName; ?>::getInstance(); 
    }
    
    
    # Accessors
<?php
    foreach($this->m_fields as $fieldName)
    {
        print '    public function get_' . $fieldName . '() { return $this->m_' . $fieldName . '; }' . PHP_EOL;
    }
?>
    
    
    # Setters
<?php
    foreach ($this->m_fields as $fieldName)
    {
        print '    public function set_' . $fieldName . '($x) { $this->m_' . $fieldName . ' = $x; }' . PHP_EOL;
    }
    ?>
}


<?php
    }
}
