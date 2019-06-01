<?php

    $test_mode = false;

    if(isset($_POST['update_state_submit'])) {

        $prevPageURL = $_SERVER['HTTP_REFERER'];

        //get data from form
        $mem_id_ary = $_POST['mem_id'];
        $mem_state_ary = $_POST['mem_state'];
        $mem_state_up_ary = $_POST['mem_state_up'];
        $today = date('Y-m-d');
        if(isset($_POST['att_part'])){$part_num = $_POST['att_part'];}

        //check and update DB
        $idx=0;
        $idx_max = count($mem_id_ary);
        if($test_mode) {
            echo 'part : '.$part_num.'<br>';
            echo 'member_count : '.$idx_max.'<br>';
        }

        include 'dbconn.php';
        for($idx=0; $idx<$idx_max; $idx++) {

            if($test_mode) {
                echo '_LOG : '.$mem_id_ary[$idx].'/'.$mem_state_ary[$idx].'/'.$mem_state_up_ary[$idx].'<br>';
            }

            if($mem_state_up_ary[$idx] != 0) {
                if($mem_state_ary[$idx] != $mem_state_up_ary[$idx]) {

                    $query_insert = "INSERT INTO member_state (id, state_update_date, state) VALUES (".$mem_id_ary[$idx].", '".$today."', ".$mem_state_up_ary[$idx].");";
                    $stmt_insert = $conn->prepare($query_insert);
                    $stmt_insert->execute();
                    if($test_mode) {
                        echo '_INSERT : <br>';
                    }

                    $query = "SELECT * FROM member_info WHERE id=".$mem_id_ary[$idx].";";
                    $stmt = $conn->prepare($query);
                    $stmt->execute();
                    $stmt->setFetchMode(PDO::FETCH_ASSOC);
                    $sn = 0;
                    while($row = $stmt->fetch()) {
                        $sn = $row['sn'];
                    }

                    $query_update = "UPDATE member_info SET last_state=".$mem_state_up_ary[$idx]." WHERE sn=".$sn.";";
                    $stmt_update = $conn->prepare($query_update);
                    $stmt_update->execute();
                    if($test_mode) {
                        echo '_UPDATE : <br>';
                    }

                }
            }
        }
        
        if($test_mode != true) {
            //redirect
            header("Location: ".$prevPageURL);
        }
    } else {
        //do nothing
    }

?> 