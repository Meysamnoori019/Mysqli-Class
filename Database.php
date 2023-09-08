<?php
/*
Class: Database
Type: MySQLi
Author: Meysam Noori - t.me/meysamtech010 
Version: 1.0.0
*/

class Database
{
    public $Database;

    public function __construct($host, $username, $password, $database)
    {
        $this->Database = new mysqli($host, $username, $password, $database);
        if ($this->Database->connect_error) {
            die('Connection failed: ' . $this->Database->connect_error);
        }
    }
    public function __destruct()
    {
        $this->Database->close();
    }
    public function query($query)
    {
        return $this->Database->query($query);
    }
    public function fetch_acsoc($result)
    {
        return $result->fetch_assoc();
    }
    public function SelectAll($table)
    {
        $query = "SELECT * FROM $table";
        $result = $this->query($query);
        if($result->num_rows > 0){
            while($row = $this->fetch_acsoc($result)){
                $rows[] = $row;
            }
            return $rows;
        }else{
            return false;
        }
    }
    public function Select($table, $columns, $where = null)
    {
        $query = "SELECT $columns FROM $table";
        if ($where != null) {
            $query .= " WHERE $where";
        }
        $result = $this->query($query);
        if($result->num_rows > 0){
            while($row = $this->fetch_acsoc($result)){
                $rows[] = $row;
            }
            return $rows;
        }else{
            return false;
        }
    }
    public function Insert($table, $data = [])
    {
        $query = "INSERT INTO $table (";
        foreach ($data as $key => $value) {
            $query .= "$key,";
        }
        $query = rtrim($query, ',');
        $query .= ") VALUES (";
        foreach ($data as $key => $value) {
            $query .= "'$value',";
        }
        $query = rtrim($query, ',');
        $query .= ")";
        if ($this->query($query)) {
            return true;
        } else {
            return false;
        }   
    }
    public function Update($table, $data = [], $where)
    {
        $query = "UPDATE $table SET ";
        foreach ($data as $key => $value) {
            $query .= "$key = $value,";
        }
        $query = rtrim($query, ',');
        $query .= " WHERE $where";
        if ($this->query($query)) {
            return true;
        } else {
            return false;
        }
    }
    public function Delete($table, $where)
    {
        $query = "DELETE FROM $table WHERE $where";
        if ($this->query($query)) {
            return true;
        } else {
            return false;
        }
    }
    public function Count($table, $where = null)
    {
        $query = "SELECT * FROM $table";
        if ($where != null) {
            $query .= " WHERE $where";
        }
        $result = $this->query($query);
        return $result->num_rows;
    }
    public function LastID($table = null)
    {
        if ($table != null) {
            $query = "SELECT id FROM $table ORDER BY id DESC LIMIT 1";
        } else {
            $query = "SELECT LAST_INSERT_ID()";
        }
        $result = $this->query($query);
        $row = $this->fetch_acsoc($result);
        return $row['id'];
    }
    public function CountNumbersValue($table, $column, $where = null)
    {
        $query = "SELECT SUM($column) AS $column FROM $table";
        if ($where != null) {
            $query .= " WHERE $where";
        }
        $result = $this->query($query);
        $row = $this->fetch_acsoc($result);
        return $row[$column];
    }
    public function Create($table, $columns)
    {
        $query = "CREATE TABLE IF NOT EXISTS $table ($columns)";
        if ($this->query($query)) {
            return true;
        } else {
            return false;
        }
    }
    public function Drop($table)
    {
        $query = "DROP TABLE IF EXISTS $table";
        if ($this->query($query)) {
            return true;
        } else {
            return false;
        }
    }
    public function Empty($table)
    {
        $query = "TRUNCATE TABLE $table";
        if ($this->query($query)) {
            return true;
        } else {
            return false;
        }
    }
    public function ExportDatabase()
    {
        $tables = array();
        $result = $this->query("SHOW TABLES");
        while ($row = mysqli_fetch_row($result)) {
            $tables[] = $row[0];
        }
        $return = '';
        foreach ($tables as $table) {
            $result = $this->query("SELECT * FROM $table");
            $num_fields = $result->field_count;
            $return .= "DROP TABLE IF EXISTS $table;\n";
            $row2 = mysqli_fetch_assoc($this->query("SHOW CREATE TABLE $table"));
            $return .= "\n\n" . $row2['Create Table'] . ";\n\n";
            while ($row = mysqli_fetch_assoc($result)) {
                $return .= "INSERT INTO $table VALUES(";
                for ($j = 0; $j < $num_fields; $j++) {
                    $column_name = mysqli_fetch_field_direct($result, $j)->name;
                    $row[$column_name] = mysqli_real_escape_string($this->Database, $row[$column_name]);
                    if (isset($row[$column_name])) {
                        $return .= '"' . $row[$column_name] . '"';
                    } else {
                        $return .= '""';
                    }
                    if ($j < $num_fields - 1) {
                        $return .= ',';
                    }
                }
                $return .= ");\n";
            }

        }
        $date = date('Y-m-d');
        $handle = fopen('backup(' . $date . ').sql', 'w+');
        fwrite($handle, $return);
        fclose($handle);
    }

    public function ImportDatabase($filename)
    {
        $templine = '';
        $lines = file($filename);
        foreach ($lines as $line) {
            if (substr($line, 0, 2) == '--' || $line == '') {
                continue;
            }
            $templine .= $line;
            if (substr(trim($line), -1, 1) == ';') {
                $this->query($templine);
                $templine = '';
            }
        }
    }
    public function DropAllTables()
    {
        $tables = array();
        $result = $this->query("SHOW TABLES");
        while ($row = mysqli_fetch_row($result)) {
            $tables[] = $row[0];
        }
        foreach ($tables as $table) {
            $this->Drop($table);
        }
    }
}
?>
