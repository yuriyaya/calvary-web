<?php

    if(isset($_POST['submit'])) {
        include_once 'dbconn.php';

        $user_id = $_POST['user_id'];
        $user_pw = $_POST['user_pw'];
        $user_pw_before = $_POST['user_pw_before'];
        // echo $user_id.'<br>';
        // echo $user_pw.'<br>';

        if(empty($user_id) || empty($user_pw)) {
            //error
            header("Location: ../admin_login.php?register=9001");
            exit();
        } else {
            //check user id first
            $search_query = "SELECT * FROM user_login WHERE user_id='$user_id'";
            if(isset($conn)) {
                $stmt = $conn->prepare($search_query);
                $stmt->execute();
                $num_of_rows = $stmt->rowCount();

                if($num_of_rows == 1) {
                    $stmt->setFetchMode(PDO::FETCH_ASSOC);
                    while($row = $stmt->fetch()) {
                        $password_before = $row['user_pw'];
                        $sn = $row['sn'];
                    }
                    if(password_verify($user_pw_before, $password_before)) {
                        //update new password
                        $hash_user_pw = password_hash($user_pw, PASSWORD_DEFAULT);
                        $query_update = "UPDATE user_login SET user_pw='".$hash_user_pw."' WHERE sn=".$sn.";";
                        $stmt_update = $conn->prepare($query_update);
                        $stmt_update->execute();
                        header("Location: ../admin_login.php?register=9005");
                        exit();
                    } else {
                        //password verification error
                        header("Location: ../admin_login.php?register=9002");
                        exit();
                    }
                } else {
                    //add new user id
                    $hash_user_pw = password_hash($user_pw, PASSWORD_DEFAULT);
                    $query = "INSERT INTO user_login (user_id, user_pw) VALUES ('$user_id', '$hash_user_pw')";
                    $conn->exec($query);
                    header("Location: ../admin_login.php?register=9004");
                    exit();
                }
            } else {
                echo 'no connection';
            }
        }

    } else {
        header("Location: ../admin_login.php");
        exit();
    }

?>