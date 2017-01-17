<?php

    $GLOBALS['serverName'] = "127.0.0.1";
    $GLOBALS['userName'] = "root";
    $GLOBALS['password'] = "";
    $GLOBALS['databseName'] = "statsanalysis";


    function connectDatabase() {
        $link = mysqli_connect($GLOBALS['serverName'], $GLOBALS['userName'], $GLOBALS['password'], $GLOBALS['databseName']);

        if (!$link) {
            echo "Error: Unable to connect to MySQL." . PHP_EOL;
            echo "Debugging errno: " . mysqli_connect_errno() . PHP_EOL;
            echo "Debugging error: " . mysqli_connect_error() . PHP_EOL;
            exit;
        }

        return $link;
    }

?>