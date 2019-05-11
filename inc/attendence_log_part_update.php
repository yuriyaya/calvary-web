<?php

    $test_mode = false;

    if(isset($_POST['update_submit'])) {

        $prevPageURL = $_SERVER['HTTP_REFERER'];

        //get data from form
        $mem_id_ary = $_POST['mem_id'];
        $mem_name_ary = $_POST['mem_name'];
        $mem_state_ary = $_POST['mem_state'];
        $mem_att_val_ary_temp = $_POST['mem_att_val'];
        $mem_state_up_ary = $_POST['mem_state_up'];
        $att_part = $_POST['att_part'];
        $att_date = $_POST['att_date'];

        $mem_att_val_ary  = array();

        for($idx=0; $idx<count($mem_att_val_ary_temp); $idx++){
            if($mem_att_val_ary_temp[$idx]=='off'){
                //
            } else {
                array_pop($mem_att_val_ary);
            }
            array_push($mem_att_val_ary, $mem_att_val_ary_temp[$idx]);
        }

        //check and update DB
        $idx=0;
        $idx_max = count($_POST['mem_id']);
        if($test_mode) {
            echo 'part : '.$att_part.'<br>';
            echo 'att log date : '.$att_date.'<br>';
        }
        include 'dbconn.php';
        for($idx=0; $idx<$idx_max; $idx++) {

            if($test_mode) {
                echo '_LOG : '.$mem_id_ary[$idx].'/'.$mem_name_ary[$idx].'/'.$mem_state_ary[$idx].'/'.$mem_att_val_ary[$idx].'/'.$mem_state_up_ary[$idx].'<br>';
            }

            $query = "SELECT * FROM ".getAttDBName($att_part)." WHERE date='".$att_date."' AND id=".$mem_id_ary[$idx].";";

            // if($test_mode) {
            //     echo $query.'<br>';
            // }

            $stmt = $conn->prepare($query);
            $stmt->execute();
            $num_of_rows = $stmt->rowCount();
            $att_value = getAttendValue($mem_att_val_ary[$idx]);

            if($num_of_rows == 1) {
                //already attendence log exist, update data
                $stmt->setFetchMode(PDO::FETCH_ASSOC);
                while($row = $stmt->fetch()) {
                    $sn = $row['sn'];
                    if($row['attend_value'] != $att_value) {
                        $query_update = "UPDATE ".getAttDBName($att_part)." SET attend_value=".$att_value." WHERE sn=".$sn.";";
                        $stmt_update = $conn->prepare($query_update);
                        $stmt_update->execute();
                        if($test_mode) {
                            echo '_UPDATE : <br>';
                        }
                    }
                }
            } else if($num_of_rows == 0) {
                //no data, insert
                $query_insert = "INSERT INTO ".getAttDBName($att_part)."(id, date, attend_value) VALUES (".$mem_id_ary[$idx].", '".$att_date."', ".$att_value.");";
                $stmt_insert = $conn->prepare($query_insert);
                $stmt_insert->execute();
                if($test_mode) {
                    echo '_INSERT : <br>';
                }
            } else if($num_of_rows > 1) {
                //error
                if($test_mode) {
                    echo '_ERROR-duplicate log : <br>';
                }
            } else {
                //error, do nothing
                echo '_ERROR-?????<br>';
            }
        }
        
        if($test_mode != true) {
            //redirect
            // header("Location: ../attendence_log_part.php?part_num=".$att_part."&id=".$record_id);
            header("Location: ".$prevPageURL);
        }
    } else {
        //do nothing
    }

    function getAttendValue($ckbox_in) {
        $ret_val = 0;

        if($ckbox_in == 'on') {
            $ret_val = 10; //prepare e.g. 지각...? or other type of attendence log
        } else {
            $ret_val = 0;
        }
        return $ret_val;
    }

    function getAttDBName($part_num) {
        $ret_db = '';

        switch($part_num){
            case 1:
                $ret_db = "attendence_sopa";
                break;
            case 2:
                $ret_db = "attendence_sopb";
                break;
            case 3:
                $ret_db = "attendence_sopbp";
                break;
            case 4:
                $ret_db = "attendence_altoa";
                break;
            case 5:
                $ret_db = "attendence_altob";
                break;
            case 6:
                $ret_db = "attendence_tenor";
                break;
            case 7:
                $ret_db = "attendence_bass";
                break;
            default:
                break;
        }

        return $ret_db;
    }

?> 