<?php

namespace Programster\OrmGenerator;

/*
 * A generator to run against a PostgreSQL database.
 */

class PgSqlGenerator
{
    private $m_db;
    private string $m_outputDirectory;


    public function __construct($connection, string $outputDirectory)
    {
        $this->m_db = $connection;

        if (is_dir($outputDirectory) === FALSE)
        {
            throw new \Exception("That directory does not exist.");
        }

        $this->m_outputDirectory = $outputDirectory;
    }


    public function run()
    {
        $tables = $this->fetchTableNames();

        foreach ($tables as $tableName)
        {
            $fields = $this->getFieldsForTable($tableName);

            $baseFilename = $this->convertTableNameToFilename($tableName);
            $tableClassName = $baseFilename . "Table";
            $objectClassName = $baseFilename . "Object";

            $objectClassCreator = new ObjectClassCreator($objectClassName, $tableClassName, $fields);
            $tableClassCreator = new TableClassCreator($tableClassName, $objectClassName, $tableName);

            file_put_contents("{$this->m_outputDirectory}/{$tableClassName}.php", $tableClassCreator);
            file_put_contents("{$this->m_outputDirectory}/{$objectClassName}.php", $objectClassCreator);
        }
    }


    /**
     * Converts a database table name such as "user_field" to "UserField"
     * @param string $tableName
     * @return string - the filename representation for a table name
     */
    private function convertTableNameToFilename(string $tableName) : string
    {
        $parts = explode("_", $tableName);

        foreach ($parts as $index => $part)
        {
            $parts[$index] = ucfirst($part);
        }

        $implodedParts = implode($parts);
        $removedUnderscores = str_replace("_", "", $implodedParts);
        return $removedUnderscores;
    }


    /**
     * Fetch the list of tables that exist in the database. This will cache the result so that subsequent calls will
     * return almost immediately.
     * @return Array<String> - list of table names
     */
    private function fetchTableNames()
    {
        $tables = array();

        if (false)
        {
            $query = "SHOW TABLES";
            $result = $this->m_db->query($query);

            while (($row = $result->fetch_array()) != null)
            {
                $tables[] = $row[0];
            }
        }
        else
        {
            $query =
                "SELECT table_name
                FROM information_schema.tables
                WHERE table_schema = 'public'
                ORDER BY table_name;";

            $result = pg_query($query);

            if ($result === false)
            {
                throw new Exception("Failed to select table names.");
            }

            while (($row = pg_fetch_assoc($result)) !== false)
            {
                $tables[] = $row['table_name'];
            }
        }

        return $tables;
    }


    /**
     * Get the array list of field names for the specified table.
     * @param string $tableName
     */
    private function getFieldsForTable(string $tableName) : array
    {
        $fields = array();
        $metadata = pg_meta_data($this->m_db, $tableName);

        foreach ($metadata as $fieldname => $info)
        {
            $fields[] = $fieldname;
        }

        return $fields;
    }
}


