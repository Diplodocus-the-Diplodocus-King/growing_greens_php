<?php

    include('config.php');
    // connect to db
    // mysqli_connect(host, username, password, database)
    $connection = mysqli_connect('localhost', $user, $password, 'growing_greens');

    //check connection
    if(!$connection){
        echo 'Connection error:' . mysqli_connect_error();
    }
?>