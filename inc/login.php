<?php

    session_start();

    if(isset($_POST['submit'])) {
        include 'dbconn.php';
        $user_id = $_POST['user_id'];
        $user_pw = $_POST['user_pw'];
        // echo $user_id.'<br>';
        // echo $user_pw.'<br>';

        if(empty($user_id) || empty($user_pw)) {
            //error
        } else {
            try {
                $query = "SELECT * FROM user_login WHERE user_id='".$user_id."'";
                $stmt = $conn->prepare($query); 
                $stmt->execute();

                $stmt->setFetchMode(PDO::FETCH_ASSOC);
                while($row= $stmt->fetch()) {
                    // echo $row['user_id'].'<br>';
                    if(password_verify($user_pw, $row['user_pw'])) {
                        //correct password
                        $_SESSION['u_id'] = $row['user_id'];
                        header("Location: ../index.php");
                    } else {
                        header("Location: ../index.php?login=error");
                    }
                }
            }
            catch(PDOException $e)
            {
                echo $query . "<br>" . $e->getMessage();
            }
        }
    }

?>