<?php

    if(isset($_POST['submit'])) {
        include_once 'dbconn.php';

        $user_id = $_POST['user_id'];
        $user_pw = $_POST['user_pw'];
        // echo $user_id.'<br>';
        // echo $user_pw.'<br>';

        if(empty($user_id) || empty($user_pw)) {
            //error
            header("Location: ../admin.php?register=empty_err");
            exit();
        } else {

            $hash_user_pw = password_hash($user_pw, PASSWORD_DEFAULT);
            $query = "INSERT INTO user_login (user_id, user_pw) VALUES ('$user_id', '$hash_user_pw')";
            // echo $query.'<br>';
            if(isset($conn)) {
                $conn->exec($query);
                // echo $user_id." registered";
                header("Location: ../admin.php?register=add_user_success");
            } else {
                echo 'no connection';
            }
        }

    } else {
        header("Location: ../admin.php");
        exit();
    }


    // $stmt=$db->prepare(“INSERT INTO test (name) VALUES (:col2)”); 
    // // 첫번째열은 auto_increment 이므로 삽입할 필요가 없다.
    // $stmt->bindParam(‘:col2′,$data2);
    // $data2=”Kelvin”;
    // $stmt->execute();

?>