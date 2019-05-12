<?php

    $status_msg_code = "";
    $member_sn = 0;
    $member_state_now = 0;

    if(isset($_POST['member_id'])){$member_id = $_POST['member_id'];}
    if(isset($_POST['member_name'])){$member_name = $_POST['member_name'];}
    if(isset($_POST['member_part'])){$member_part = $_POST['member_part'];}
    if(isset($_POST['state_update_date'])){$state_update_date = $_POST['state_update_date'];}
    if(isset($_POST['member_state_now'])){$member_state_now = $_POST['member_state_now'];}
    if(isset($_POST['member_state'])){$member_state = $_POST['member_state'];}

    if(isset($_POST['member_state_update'])) {

        if(empty($member_id) || empty($member_name) || empty($member_part) || empty($state_update_date) || empty($member_state)) {
            //error
            $status_msg_code = "9030";
        } else {
            try {
                include_once 'dbconn.php';

                // echo $member_id.'<br>';
                // echo $member_name.'<br>';
                // echo $member_part.'<br>';
                // echo $state_update_date.'<br>';
                // echo $member_state_now.'<br>';
                // echo $member_state.'<br>';

                //check update state is not same with current state
                if($member_state_now == $member_state){
                    //not changed, do nothing
                    $status_msg_code = "9032";
                } else {
                    //add record to database
                    $query = "INSERT INTO member_state (id, state_update_date, state) VALUES (:in1, :in2, :in3)";
                    $stmt = $conn->prepare($query);
                    $stmt->bindParam(':in1', $in1);
                    $stmt->bindParam(':in2', $in2);
                    $stmt->bindParam(':in3', $in3);

                    $in1 = $member_id;
                    $in2 = $state_update_date;
                    $in3 = $member_state;
                    $stmt->execute();

                    $status_msg_code = "9031";
                }
            }
            catch(PDOException $e)
            {
                echo $query . "<br>" . $e->getMessage();
            }
        }
        unset($_POST['member_state_update']);

    } elseif (isset($_POST['member_search'])) {

        if(empty($member_name) || empty($member_part) ) {
            //error
            $status_msg_code = "9013";
        } else {
            try {
                include_once 'dbconn.php';

                //search name
                if(!empty($member_id)) {
                    $query = "SELECT * FROM member_info WHERE part=".$member_part." AND name='".$member_name."' AND id=".$member_id;
                } else {
                    $query = "SELECT * FROM member_info WHERE part=".$member_part." AND name='".$member_name."'";
                }
                // echo $query;
                $stmt = $conn->prepare($query);
                $stmt->execute();
                $num_of_rows = $stmt->rowCount();
                // echo "num_of_rows: ".$num_of_rows."<br>";
                if($num_of_rows > 1) {
                    $status_msg_code = "9014";
                    $stmt->setFetchMode(PDO::FETCH_ASSOC);
                    $additional_info='<table class="w3-table-all w3-hoverable"><tr><th>일련번호</th><th>이름</th><th>파트</th><th>등록일</th><th>직분</th></tr>';
                    while($row = $stmt->fetch()) {
                        $additional_info=$additional_info.'<tr>';
                        $additional_info=$additional_info.'<td>'.$row['id'].'</td>';
                        $additional_info=$additional_info.'<td>'.$row['name'].'</td>';
                        $additional_info=$additional_info.'<td>'.returnPartName($row['part']).'</td>';
                        $additional_info=$additional_info.'<td>'.$row['join_date'].'</td>';
                        $additional_info=$additional_info.'<td>'.returnChurchStaffName($row['church_staff']).'</td>';
                        $additional_info=$additional_info.'</tr>';
                    }
                    $additional_info=$additional_info.'</table>';
                    
                } else if($num_of_rows == 1) {
                    //1 person exist, update form
                    $stmt->setFetchMode(PDO::FETCH_ASSOC);
                    while($row = $stmt->fetch()) {
                        $member_sn = $row['sn'];
                        $member_id = $row['id'];
                        $member_staff = $row['church_staff'];
                        $calvary_staff = $row['calvary_staff'];
                    }

                    //get current member state from member_state database
                    $query = "SELECT * FROM member_state WHERE id=".$member_id." ORDER BY sn DESC;";

                    $stmt = $conn->prepare($query);
                    $stmt->execute();
                    $num_of_rows = $stmt->rowCount();
                    $member_state_now = 0;
                    if($num_of_rows >= 1) {
                        $stmt->setFetchMode(PDO::FETCH_ASSOC);
                        $state_change_history='<table class="w3-table-all w3-hoverable"><tr><th>변경일</th><th>상태</th>';
                        while($row = $stmt->fetch()) {
                            if(empty($member_state_now)) {
                                $member_state_now = $row['state'];
                            }
                            $state_change_history=$state_change_history.'<tr>';
                            $state_change_history=$state_change_history.'<td>'.$row['state_update_date'].'</td>';
                            $state_change_history=$state_change_history.'<td>'.getMemberStateString($row['state']).'</td>';
                            $state_change_history=$state_change_history.'</tr>';
                        }
                        $state_change_history=$state_change_history.'</table>';
                    } else {
                        $member_state_now = 0;
                    }

                    $status_msg_code = "9016";
                } else if($num_of_rows == 0) {
                    //member not exist
                    $member_id = 0;
                    $member_join_date = '';
                    $member_staff = 0;
                    $calvary_staff = 0;
                    $status_msg_code = "9015";
                }
            }
            catch(PDOException $e)
            {
                echo $query . "<br>" . $e->getMessage();
            }
        }
        unset($_POST['member_search']);
    } else {}

?>