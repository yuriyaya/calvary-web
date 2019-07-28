<?php

    $status_msg_code = "";
    $week_day = null;
    $search_result_info = "";
    $record_sn = null;

    if(isset($_POST['att_date'])){$att_date = $_POST['att_date'];}
    if(isset($_POST['att_date_desc'])){$att_date_desc = $_POST['att_date_desc'];}
    if(isset($_POST['att_date_start'])){$att_date_start = $_POST['att_date_start'];}
    if(isset($_POST['att_date_end'])){$att_date_end = $_POST['att_date_end'];}

    if(isset($_POST['att_date_add_submit'])) {

        if(empty($att_date)) {
            //error
            $status_msg_code = "9040";
        } else {
            try {
                include_once 'dbconn.php';

                // echo $att_date.'<br>';
                // echo $att_date_desc.'<br>';

                //check whether same name is already registered in same part
                $query = "SELECT * FROM attendence_date WHERE att_date='".$att_date."'";
                $stmt = $conn->prepare($query);
                $stmt->execute();
                $num_of_rows = $stmt->rowCount();
                // echo "num_of_rows: ".$num_of_rows."<br>";
                if($num_of_rows>0) {
                    $status_msg_code = "9041";
                } else {
                    //date not exist, add it
                    $week_day = date('w', strtotime($att_date));
                    // echo $week_day;
                    $query = "INSERT INTO attendence_date (att_date, type, details) VALUES (:in1, :in2, :in3)";
                    $stmt = $conn->prepare($query);
                    $stmt->bindParam(':in1', $in1);
                    $stmt->bindParam(':in2', $in2);
                    $stmt->bindParam(':in3', $in3);

                    $in1 = $att_date;
                    $in2 = $week_day;
                    $in3 = $att_date_desc;
                    $stmt->execute();
                    
                    $status_msg_code = "9042";
                }
            }
            catch(PDOException $e)
            {
                echo $query . "<br>" . $e->getMessage();
            }
        }
        unset($_POST['att_date_submit']);

    } elseif (isset($_POST['att_date_delete_submit'])) {
        if(empty($att_date)) {
            //error
            $status_msg_code = "9040";
        } else {
            try {
                include_once 'dbconn.php';

                //to delete attendence date, get sn
                $query = "SELECT * FROM attendence_date WHERE att_date='".$att_date."'";
                $stmt = $conn->prepare($query);
                $stmt->execute();
                $num_of_rows = $stmt->rowCount();
                // echo "num_of_rows: ".$num_of_rows."<br>";
                if($num_of_rows > 0) {
                    $stmt->setFetchMode(PDO::FETCH_ASSOC);
                    while($row = $stmt->fetch()) {
                        $record_sn = $row['sn'];
                    }

                    $query = "DELETE FROM attendence_date WHERE sn=".$record_sn;
                    $stmt = $conn->prepare($query);
                    $stmt->execute();

                    $status_msg_code = "9044";

                } else {
                    $status_msg_code = "9043";
                }
            }
            catch(PDOException $e)
            {
                echo $query . "<br>" . $e->getMessage();
            }
        }
        unset($_POST['att_date_delete_submit']);
    } elseif (isset($_POST['att_date_search_submit'])) {

        if(empty($att_date_start) || empty($att_date_end) ) {
            //error
            $status_msg_code = "9045";
        } else {
            try {
                include_once 'dbconn.php';

                //search attendence date
                $query = "SELECT * FROM attendence_date WHERE att_date >='".$att_date_start."' AND att_date <='".$att_date_end."' ORDER BY att_date DESC";
                // echo $query;
                $stmt = $conn->prepare($query);
                $stmt->execute();
                $stmt->setFetchMode(PDO::FETCH_ASSOC);
                $search_result_info='<table class="w3-table-all w3-hoverable"><tr><th>출결일</th><th>요일</th><th>설명</th></tr>';
                while($row = $stmt->fetch()) {
                    $search_result_info=$search_result_info.'<tr>';
                    $search_result_info=$search_result_info.'<td>'.$row['att_date'].'</td>';
                    $search_result_info=$search_result_info.'<td>'.getDay($row['type']).'</td>';
                    $search_result_info=$search_result_info.'<td>'.$row['details'].'</td>';
                    $search_result_info=$search_result_info.'</tr>';
                }
                $search_result_info=$search_result_info.'</table>';
                $status_msg_code = "9046";
            }
            catch(PDOException $e)
            {
                echo $query . "<br>" . $e->getMessage();
            }
        }
        unset($_POST['att_date_search_submit']);
    } else {}

?>