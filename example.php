<?php
/*
Example For Database Class
*/
// Include Database Class
require_once 'Database.php';



// Create Database Object
$db_details = [
    'host'   => 'localhost',// Your Database Host
    'username' => 'root',// Your Database Username
    'password' => '',// Your Database Password
    'database' => 'test',// Your Database Name
];
$db = new Database($db_details['host'], $db_details['username'], $db_details['password'], $db_details['database']);



// Create Table On Database If Not Exists
/*
First Parameter: Table Name = string
Second Parameter: Columns = array
*/
$db->Create('users', [
    'id' => 'INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY',
    'firstname' => 'VARCHAR(30) NOT NULL',
    'lastname' => 'VARCHAR(30) NOT NULL',
    'email' => 'VARCHAR(50)',
    'reg_date' => 'TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP'
]);




// Insert Data To Table
/*
First Parameter: Table Name = string
Second Parameter: Data = array
*/
$db->Insert('users', [
    'firstname' => 'John',
    'lastname' => 'Doe',
    'email' => 'jojndoe@exmple.com',
]);




// Update Data On Table
/*
First Parameter: Table Name = string
Second Parameter: Data = array
Third Parameter: Where = string
*/
$db->Update('users', [
    'firstname' => 'John',
    'lastname' => 'Doe',
    'email' => 'test@exmple.com',
], 'id = 1');



// Delete Data From Table
/*
First Parameter: Table Name = string
Second Parameter: Where = string
*/
$db->Delete('users', 'id = 1');




// Select One Column From Table
/*
First Parameter: Table Name = string
Second Parameter: Column Name = string
Third Parameter: Where = string (Optional) (Default: '')
*/
$db->Select('users', 'firstname', 'id = 1');




// Select All Columns From Table
/*
First Parameter: Table Name = string
*/
$db->SelectAll('users');




// Get Last Inserted ID
/*
First Parameter: Table Name = string
*/
$db->LastID('users');




// Count All Rows On Table
/*
First Parameter: Table Name = string
Second Parameter: Where = string (Optional) (Default: '')
*/
$db->Count('users', 'id = 1');




// Count Sum Of Int Columns On Table
/*
First Parameter: Table Name = string
Second Parameter: Column Name = string
Third Parameter: Where = string (Optional) (Default: '')
*/
$db->CountNumbersValue('users', 'id', 'id = 1');





// Empty Table
/*
First Parameter: Table Name = string
*/
$db->Empty('users');   // Delete All Rows From Table





// Drop Table
/*
First Parameter: Table Name = string
*/
$db->Drop('users');    // Delete Table




// Backup Database
$db->ExportDatabase(); // Export Database To SQL File




// Import Database
$db->ImportDatabase('test.sql'); // Import Database From SQL File





// Drop All Tables
$db->DropAllTables(); // Drop All Tables From Database




// Close Connection
$db->__destruct(); // Close Connection


/*
Please Report Any Bug Or Problem To t.me/meysamtech010 
Author: Meysam Noori
*/
?>