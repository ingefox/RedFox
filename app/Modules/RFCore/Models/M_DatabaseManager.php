<?php

namespace RFCore\Models;

use CodeIgniter\Database\ConnectionInterface;
use CodeIgniter\Validation\ValidationInterface;
use Exception;
use File;
use ZipArchive;

class M_DatabaseManager extends RF_Model
{
    const MAXROW = 50;
    public function __construct(ConnectionInterface &$db = null, ValidationInterface $validation = null)
    {
        parent::__construct($db, $validation);
    }

    /**
     * uses Doctrine & exportDataSql() to make an entire export of database (structure + data)
     *
     * @return string
     */
    public function exportDatabaseSql()
    {
        $database = '';
        $M_Doctrine = new M_Doctrine();
        $data = $M_Doctrine->createSchemaSql();
        $database .= $data['structure'];

        $database .= "\r".$this->exportDataSql();

        return $database;
    }

    /**
     * fetch data from database in sql file
     * INSERT INTO (field) VALUES (value)
     *
     * @return string
     */
    public function exportDataSql()
    {
        $ret ='';
        $lastTable = '';
        $db_name = config('Database')->default["database"];

        $db = db_connect();
        $tables = $this->db->query("SHOW TABLES FROM `".$db_name."`")->getResultArray();

        //fetch data
        foreach($tables as $key => $val)
        {
            $table =  $val['Tables_in_'.$db_name];
            $sql = "SELECT * FROM ".$table;
            $tableData = $db->query($sql)->getResultArray();
            $lastRow = end($tableData);

            foreach($tableData as $row)
            {

                $rowColumns = '';
                $rowValues = '';
                $lastKey = array_key_last($row);
                foreach($row as $key => $val)
                {
                    $rowColumns .= $key;
                    $rowValues .= $db->escape($val);
                    if($lastKey != $key)
                    {
                        $rowColumns .= ",";
                        $rowValues .= ",";
                    }

                }

                //format SQL and set maxium row per query
				$count = 0;
				if($table != $lastTable || $count == M_DatabaseManager::MAXROW)
                {
                    $ret .= "INSERT INTO `".$table."`"." (".$rowColumns.") VALUES\r(".$rowValues."),\r\n";
                    $count = 0;
                }
                else
                {
                    if($count != M_DatabaseManager::MAXROW-1)
                    {
                        $ret .= "(".$rowValues.")";
                        if($lastRow != $row) $ret .= ",\r\n";
                        else $ret .= ";\r\n";
                    }
                    else
                    {
                        $ret .= "(".$rowValues.");\r\n";
                    }
                }

                $lastTable = $table;
                $count++;
            }

        }

        return $ret;

    }


    /**
     * creates temp file
     * exports data to csv files
     * put csv files to zip files as temp
     *
     *
     * @return false|File|string
     */
    public function exportDataCsv()
    {
    	$tempPath = dirname(__DIR__,4).DIRECTORY_SEPARATOR.'public'.DIRECTORY_SEPARATOR."temp".DIRECTORY_SEPARATOR;
        $tempFile = tempnam($tempPath, "zip");

        $ret = null;
        $db_name = config('Database')->default["database"];
        $zip = new ZipArchive;

        $db = db_connect();
        $zip->open($tempFile, ZipArchive::CREATE || ZipArchive::OVERWRITE);

        //get every tables's name
        $tables = $this->db->query("SHOW TABLES FROM `".$db_name."`")->getResultArray();


        try
        {
            //output data from each table to csv file and put it in zip
            foreach($tables as $key => $val)
            {
                $table =  $val['Tables_in_'.$db_name];
                $content = '';

                //get columns name
                $columns = $this->db->query("SHOW COLUMNS FROM `".$table."`")->getResultArray();
                $lastCol = end($columns);
                $headers = '';

                //make headers line
                foreach($columns as $c)
                {
                    if ($c['Field'] == $lastCol['Field'])
                        $headers .= $c['Field']."\r";
                    else
                        $headers.= $c['Field'].", ";
                }
                $content .= $headers;

                //get data and put it in csv
                $sql = "SELECT * FROM ".$table;
                $data = $db->query($sql)->getResultArray();
                foreach($data as $row)
                {
                    $lastKey = array_key_last($row);
                    foreach($row as $key => $val)
                    {
                        if($key == $lastKey) $content .= $val."\r";
                        else $content .= $val.",";
                    }
                }

                $zip->addFromString($table.".csv", $content);
            }
        }
        catch(Exception $e){
			$tempFile = $e;
        }

        $zip->close();

        //return temp file
        return $tempFile;

    }
}
