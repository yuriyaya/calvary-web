<?php

    $test_mode = false;

    if(isset($_POST['monthly_submit'])) {

        $prevPageURL = $_SERVER['HTTP_REFERER'];

        //get data from form
        $mem_id_ary = $_POST['mem_id'];
        $att_month_rate_ary = $_POST['att_month_rate'];
        $att_part = $_POST['att_part'];
        $att_date = $_POST['att_date'];

        $idx_max = count($mem_id_ary);
        include 'dbconn.php';
        for($idx=0; $idx<$idx_max; $idx++) {

            if($test_mode) {
                echo '_LOG : '.$mem_id_ary[$idx].'/'.$att_month_rate_ary[$idx].'<br>';
            }

            $month_start = date('Y-m', strtotime($att_date)).'-01';
            $query = "SELECT * FROM ".getAttMonthlyDBName($att_part)." WHERE date='".$month_start."' AND id=".$mem_id_ary[$idx].";";

            if($test_mode) {
                echo $query.'<br>';
            }

            $stmt = $conn->prepare($query);
            $stmt->execute();
            $num_of_rows = $stmt->rowCount();
            $att_value = $att_month_rate_ary[$idx];

            if($num_of_rows == 1) {
                //already attendence log exist, update data
                $stmt->setFetchMode(PDO::FETCH_ASSOC);
                while($row = $stmt->fetch()) {
                    $sn = $row['sn'];
                    if($row['att_month_rate'] != $att_value) {
                        $query_update = "UPDATE ".getAttMonthlyDBName($att_part)." SET att_month_rate=".$att_value." WHERE sn=".$sn.";";
                        $stmt_update = $conn->prepare($query_update);
                        $stmt_update->execute();
                        if($test_mode) {
                            echo '_UPDATE : <br>';
                        }
                    } else {
                        if($test_mode) {
                            echo '_NO_UPDATE_REQUIRED : <br>';
                        }
                    }
                }
            } else if($num_of_rows == 0) {
                //no data, insert
                $query_insert = "INSERT INTO ".getAttMonthlyDBName($att_part)."(id, date, att_month_rate) VALUES (".$mem_id_ary[$idx].", '".$month_start."', ".$att_value.");";
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

    function getAttMonthlyDBName($part_num) {
        $ret_db = '';

        switch($part_num){
            case 1:
                $ret_db = "attendence_month_sopa";
                break;
            case 2:
                $ret_db = "attendence_month_sopb";
                break;
            case 3:
                $ret_db = "attendence_month_sopbp";
                break;
            case 4:
                $ret_db = "attendence_month_altoa";
                break;
            case 5:
                $ret_db = "attendence_month_altob";
                break;
            case 6:
                $ret_db = "attendence_month_tenor";
                break;
            case 7:
                $ret_db = "attendence_month_bass";
                break;
            default:
                break;
        }

        return $ret_db;
    }

?> 