<?php
    // Basic connection settings
    $databaseHost = '127.0.0.1';
    $databaseUsername = 'webuser';
    //Read-only access for demo purposes:
    $databasePassword = 'gJsUGYAy3W7peM';
    $databaseName = 'recipe';

    // Connect to the database
    $mysqli = mysqli_connect($databaseHost, $databaseUsername, $databasePassword, $databaseName); 
?>
