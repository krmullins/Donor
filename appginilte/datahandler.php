<?php
/**
 * CRUD operations on a JSON noSQL database.
 *
 * Main class for creating, retreiving, updating and deleting records in a JSON noSQL database.
 *
 * @version 1.0
 * @author Dave
 */
class jsondb
{
    public $dataFolder = 'appginilte';
    /**
     * Creates database file
     *
     * @param string $dbName Name of database file to create
     * @return void
     */
    public function cDataBase($dbName)
    {
        $fileName = $this->dataFolder . '/' . $dbName . '.json';
        file_put_contents($fileName, null, FILE_APPEND);
    }
    /**
     * Retrieves database data
     *
     * @param string $dbName Name of database file to retrieve
     * @return array Data or empty if none
     */
    public function rDataBase($dbName)
    {

        $fileName = $this->dataFolder . '/' . $dbName . '.json';
        $dataString = file_get_contents($fileName);
        $data = json_decode($dataString, true);
        if (is_array($data)) {
            ksort($data);
            return $data;
        } else {
            return array();
        }
    }
    /**
     * Updates database data
     *
     * @param string $dbName Name of database file to update
     * @param array $data Data to update, an array.
     * @return void
     */
    public function uDataBase($dbName, $data)
    {
        $fileName = $this->dataFolder . '/' . $dbName . '.json';
        $jsonData = json_encode($data);
        file_put_contents($fileName, $jsonData);
    }
    /**
     * Deletes database
     *
     * @param string $dbName Name of database file to delete
     * @return void
     */
    public function dDataBase($dbName)
    {
        $fileName = $this->dataFolder . '/' . $dbName . '.json';
        unlink($fileName);
    }
    /**
     * Gets next record id
     *
     * @param array $data Database data
     * @return int Id of next record
     */
    public function nextRecord($data)
    {

        if (is_array($data)) {
            ksort($data);
            end($data);
            $id = key($data) + 1;
        } else {
            $id = 1;
        }
        return $id;
    }

    /**
    * Gets and returns all available databases
    * 
    * @return array
    */

    public function rDataBaseAll()
    {
        $files = scandir($this->dataFolder);
        $databases = [];
        $count = 0;
        foreach ($files as $file) {
            if ($file != '.' && $file != '..' && $file != "index.html" && $file != " " && strpos($file, '.json') == true) {
                $count++;
                $databases[] = str_replace(".json", "", $file);
            }
        }
        if ($count == 0) {
            return array();
        } else {
            return $databases;
        }
    }
}