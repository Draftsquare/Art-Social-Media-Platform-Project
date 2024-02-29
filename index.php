<?php

    // 1. Create a database connection
    // 2. Perform a database query
    // 3. Use the returned data from the database
    // 4. Release the return data
   //  5. Close the database connection
  
   $databaseConnection = mysqli_connect( "localhost", "root", "", "ArtWave" );

   if ( mysqli_connect_errno() ) {
        exit( "Database connection failed" );
   }

   echo ( "Connection to the database is successful" );
  
  
   if( $databaseConnection ) {
    mysqli_close( $databaseConnection );
    echo( "Connection closed");
   } else{
    echo( "There was no connection in the first place" );
   }



?>

<!DOCTYPE html>
<html>
    <head>
        <title>ArtWave</title>
        <link href="css/ArtWave.css" rel="stylesheet">
    </head>
    <body>
        <h1>Welcome to ArtWave!</h1>
        <form>
            <textarea></textarea>
            <input type="submit" value="Post">
        </form>
    </body>
</html>

<?php
 if( $databaseConnection ) {
    mysqli_close( $databaseConnection );
 }
?>