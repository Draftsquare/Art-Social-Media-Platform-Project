<?php

    // 1. Create a database connection
    // 2. Perform a database query
    // 3. Use the returned data from the database
    // 4. Release the return data
   //  5. Close the database connection
  


   $databaseConnection = mysqli_connect( "localhost", "root", "", "ArtWave" );

   if ( mysqli_connect_errno() ) {
        exit( "Database connection failed" );
   } else {
        // echo( "Success!" );
   }

   // echo ( "Connection to the database is successful" );
  
   // Temporary user id
   $userId = 1;

   if( isset( $_GET['userDeleteId'])) {
        $userDeleteId = mysqli_real_escape_string( $databaseConnection, $_GET['userDeleteId']);

        $sql = "DELETE FROM posts ";
        $sql .= "WHERE id='" . $userDeleteId . "'";

       $postDeletionSuccessful = mysqli_query( $databaseConnection, $sql );
       if( $postDeletionSuccessful ) {
        // echo("Post Sueccessfully inserted");
        header("Location: index.php");
        exit();
    } else {
     echo( mysqli_error ($databaseConnection) );

     if( $databaseConnection ) {
         mysqli_close( $databaseConnection );
      }

     exit();
    }

   }
   

   if( $_SERVER[ 'REQUEST_METHOD'] == 'POST' && isset( $_POST['postButtonClicked'] ) ) {
        $postContent = mysqli_real_escape_string ( $databaseConnection, $_POST['postContent'] );

        $sql = "INSERT INTO posts (";
        $sql .= "postContent, userid)";
        $sql .= " VALUES ( ";
        $sql .= "'" . $postContent . "', ";
        $sql .= "'". $userId . "'";
        $sql .= ")";

       /*() echo($sql);
        exit(); */

        $postInsertionSuccessful = mysqli_query( $databaseConnection, $sql );

       if( $postInsertionSuccessful ) {
           // echo("Post Sueccessfully inserted");
       } else {
        echo( mysqli_error ($databaseConnection) );

        if( $databaseConnection ) {
            mysqli_close( $databaseConnection );
         }

        exit();
       }
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
        <form action="index.php" method="post">
            <textarea name="postContent"></textarea>
            <input type="submit" value="Post" name="postButtonClicked">
        </form>
        <?php
            $sql = "SELECT * FROM posts";

            $allPosts = mysqli_query($databaseConnection, $sql);
            // var_dump($allPosts);
            // exit();

        while ( $currentPost= mysqli_fetch_assoc( $allPosts ) ) {
            
?> 
            <article>
                <?php echo ($currentPost['date']); ?> :
                <?php echo ($currentPost['postContent']); ?> by
                <?php 
                    $sql = "SELECT * FROM users ";
                    $sql .= "WHERE id ='" . $currentPost['userid'] . "'";

                    $userOfPost = mysqli_query( $databaseConnection, $sql);
                    $userOfPost = mysqli_fetch_assoc( $userOfPost);
                ?>
                <?php echo( $userOfPost['nickname']);
                echo("<br>"); 
                ?> 
                <?php if($userOfPost['id'] == $userId ){
?>
                        <a href="<?php echo("index.php?userDeleteId=" . urlencode ($currentPost['id'] ) ); ?>">Delete</a>
<?php 
                }
                ?>
                <?php
        }
            
            ?> 
            </article>
    </body>
</html>


<?php
    if( $databaseConnection ) {
    mysqli_close( $databaseConnection );
 }
?>