<?php
    $databaseConnection = mysqli_connect( "localhost", "root", "", "ArtWave" );
    /* This is responsible for storing all errors */
    $errors = [];
    session_start();

    if ( mysqli_connect_errno() ) {
        exit( "Database connection failed" );
    }

    if( $_SERVER[ 'REQUEST_METHOD'] == 'POST' && isset( $_POST['signUpClicked'] ) ) {
        $nickname = mysqli_real_escape_string( $databaseConnection, $_POST['nickname'] );
        $password = mysqli_real_escape_string( $databaseConnection, $_POST['password'] );

        $hashedPassword = password_hash( $password, PASSWORD_DEFAULT );

        $sql = "INSERT INTO users";
        $sql .= "( nickname, hashedPassword ) ";
        $sql .= " VALUES ( ";
        $sql .= "'" . $nickname . "', ";
        $sql .= "'". $hashedPassword . "'";
        $sql .= ")";

       

        $userInsertedSuccessfully = mysqli_query( $databaseConnection, $sql);

        $userInsertedSuccessfully = mysqli_query( $databaseConnection, $sql );
    
            if( $userInsertedSuccessfully ) {
                // echo( "User inserted successfully" );
                header("Location: login.php");
                exit();
            } else {
                echo( mysqli_error ($databaseConnection) );
    
                if( $databaseConnection ) {
                    mysqli_close( $databaseConnection );
                }
                exit();
            }
        
    }
?>
<h3>Sign up</h3>
<form action="signup.php" method="post">
    <input type="text" name="nickname" placeholder="Nickname">
    <input type="password" name="password" placeholder="password">
    <input type="submit" value="Join" name="signUpClicked">
</form>