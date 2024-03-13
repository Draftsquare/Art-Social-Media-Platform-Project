<?php
    $databaseConnection = mysqli_connect( "localhost", "root", "", "ArtWave" );
    /* This is responsible for storing all errors */
    $errors = [];
    session_start();

    if ( mysqli_connect_errno() ) {
        exit( "Database connection failed" );
    }

    unset( $_SESSION['userId'] );
    unset( $_SESSION['nickname'] );
    header("Location: login.php" );
    exit();
?>