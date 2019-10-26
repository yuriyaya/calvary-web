<?php

    $db_config_str = file_get_contents(__DIR__.'/../../db_config.json');
    $db_config = json_decode($db_config_str, true);
    if(array_key_exists('mysql', $db_config)) {
        $servername = $db_config['mysql']['servername'];
        $username = $db_config['mysql']['username'];
        $password = $db_config['mysql']['password'];
        $dbname = $db_config['mysql']['dbname'];
        // echo $servername.'<br>';
        // echo $username.'<br>';
        // echo $password.'<br>';
        // echo $dbname.'<br>';
    }

    try {
        $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
        // set the PDO error mode to exception
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $conn->setAttribute(PDO::MYSQL_ATTR_INIT_COMMAND, "SET NAMES 'utf8'");

        // echo "connection success";
    }
    catch(PDOException $e)
    {
        echo 'DB connection error: '.$e->getMessage().'<br>';
    }

?>