<?php

    // 1. Create a database connection
    // 2. Perform a database query
    // 3. Use the returned data from the database
    // 4. Release the return data
    // 5. Close the database connection
    define("SITE_CC", "I don't own the copyright");

    function formatFooterText($year, $copyright){
        return $year . " " . $copyright;
    }

    $databaseConnection = mysqli_connect( "localhost", "root", "", "ArtWave" );
    /* This is responsible for storing all errors */
    $errors = [];
    session_start();

    if ( mysqli_connect_errno() ) {
        exit( "Database connection failed" );
    }

    /* Require that anyone who accesses this page is logged in */
    if(!isset( $_SESSION['userId'] ) ){
        header("Location: login.php");
        exit();
    } else {
    ?>
        Welcome <?php echo( $_SESSION['nickname'] ); ?> 
        <a href="logout.php">Logout</a>
    <?php
        $userId = $_SESSION['userId']; 
    }

    

    /* ABOUT TO DELETE */
    if( isset( $_GET['postDeleteId'])) {
        $postDeleteId = mysqli_real_escape_string( $databaseConnection, $_GET['postDeleteId']);

        $sql = "DELETE FROM posts ";
        $sql .= "WHERE id='" . $postDeleteId . "'";

        $postDeletionSuccessful = mysqli_query( $databaseConnection, $sql );
        if( $postDeletionSuccessful ) {
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

    /* ABOUT TO EDIT */
    if( $_SERVER[ 'REQUEST_METHOD'] == 'POST' && isset( $_POST['editPostClicked'] ) ) {
        $postToEdit = mysqli_real_escape_string ($databaseConnection, $_GET['postToEdit'] );
        $updatedPost = mysqli_real_escape_string ($databaseConnection, $_POST['updatedPost'] );

        $sql = "UPDATE posts SET ";
        $sql .= "postContent='" . mysqli_real_escape_string( $databaseConnection, stripslashes($updatedPost)) . "' ";
        $sql .= "WHERE id='" . $postToEdit . "'";

        $postUpdateSuccessful = mysqli_query($databaseConnection, $sql );

        if( $postUpdateSuccessful ) {
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

    /* ABOUT TO POST */
    if( $_SERVER[ 'REQUEST_METHOD'] == 'POST' && isset( $_POST['postButtonClicked'] ) ) {
        $postContent = mysqli_real_escape_string ( $databaseConnection, $_POST['postContent'] );

        /* If the post content is not set or after trimming it, it's equal to an empty string */
        if( !isset($postContent) || trim($postContent) === "" ) {
            $errors[] = "Please type in something";
        }

        if(empty($errors ) ) {
            $sql = "INSERT INTO posts (";
            $sql .= "postContent, userid)";
            $sql .= " VALUES ( ";
            $sql .= "'" . $postContent . "', ";
            $sql .= "'". $userId . "'";
            $sql .= ")";
            
           
            
    
            $postInsertionSuccessful = mysqli_query( $databaseConnection, $sql );
    
            if( $postInsertionSuccessful ) {
                // Post inserted successfully
            } else {
                echo( mysqli_error ($databaseConnection) );
    
                if( $databaseConnection ) {
                    mysqli_close( $databaseConnection );
                }
                exit();
            }
        } 
        }

?>
     <!-- var_dump($sql);
        exit(); -->
<!DOCTYPE html>
<html>
<head>
    <title>ArtWave</title>
    <link href="css/ArtWave.css" rel="stylesheet">
</head>
<body>
    <h1>Welcome to ArtWave!</h1>
    <span class="error">
<?php
        foreach($errors as $currentError ) {
            echo("$currentError");
        }
?>
    </span>
    <form action="index.php" method="post">
        <textarea name="postContent"></textarea>
        <input type="submit" value="Post" name="postButtonClicked">
    </form>
    <?php
    $sql = "SELECT * FROM posts";

    $allPosts = mysqli_query($databaseConnection, $sql);

    while ( $currentPost = mysqli_fetch_assoc( $allPosts ) ) {
        // var_dump($currentPost);
        // exit();

    ?> 
    <article>
        <?php echo ($currentPost['date']); ?> :
        <?php echo ( htmlspecialchars($currentPost['postContent']) ); ?> by
        <?php 
            $sql = "SELECT * FROM users ";
            $sql .= "WHERE id ='" . $currentPost['userid'] . "'";

            $userOfPost = mysqli_query( $databaseConnection, $sql);
            $userOfPost = mysqli_fetch_assoc( $userOfPost);
        ?>
        <?php echo( $userOfPost['nickname']);
        echo("<br>"); 
        ?> 
        <?php if($userOfPost['id'] == $userId ){ ?>
            <a href="<?php echo("index.php?postDeleteId=" . urlencode ($currentPost['id'] ) ); ?>">Delete</a>
            <a href="<?php echo("index.php?postEditId=" . urlencode ($currentPost['id'] ) ); ?>">Edit</a>
        <?php } ?>
    </article>
    <?php
    /* ABOUT TO EDIT */
    if( isset( $_GET ['postEditId'] ) && $currentPost['id'] == $_GET['postEditId'] && $userOfPost['id'] == $userId ) {
        $postEditId = mysqli_real_escape_string( $databaseConnection, $_GET['postEditId'] );
        $sql = "SELECT * FROM posts ";
        $sql .= "WHERE id='" .$postEditId . "'";

        $postToEdit = mysqli_query($databaseConnection, $sql);
        $postToEdit = mysqli_fetch_assoc( $postToEdit);
        
    ?>
    <form action="<?php echo("index.php?postToEdit=" . urlencode( $postToEdit['id'] ) ); ?>" method="POST"> 
        <textarea name="updatedPost"><?php echo ($postToEdit['postContent']); ?></textarea>
        <input type ="submit" name="editPostClicked" value="Edit post">
    </form>
    <?php } ?>
    <?php } ?>
    <footer>
    <?php echo( formatFooterText("2024" , SITE_CC)); ?>
    </footer>
</body>
</html>


<?php
    if( $databaseConnection ) {
        mysqli_close( $databaseConnection );
    }

?>
