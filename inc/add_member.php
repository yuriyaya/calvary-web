<?php

    $status_msg_code = "";
    $member_sn = 0;

    if(isset($_POST['member_id'])){$member_id = $_POST['member_id'];}
    if(isset($_POST['member_name'])){$member_name = $_POST['member_name'];}
    if(isset($_POST['member_part'])){$member_part = $_POST['member_part'];}
    if(isset($_POST['member_staff'])){$member_staff = $_POST['member_staff'];}
    if(isset($_POST['calvary_staff'])){$calvary_staff = $_POST['calvary_staff'];}

    if(isset($_POST['member_register'])) {

        if(empty($member_name) || empty($member_part) || empty($member_staff) || empty($calvary_staff)) {
            //error
            $status_msg_code = "9010";
        } else {
            try {
                include_once 'dbconn.php';

                // echo $member_name.'<br>';
                // echo $member_part.'<br>';
                // echo $member_staff.'<br>';

                //check whether same name is already registered in same part
                $query = "SELECT * FROM member_info WHERE part=".$member_part." AND name='".$member_name."'";
                $stmt = $conn->prepare($query);
                $stmt->execute();
                $num_of_rows = $stmt->rowCount();
                // echo "num_of_rows: ".$num_of_rows."<br>";
                if($num_of_rows>0) {
                    $status_msg_code = "9012";
                }

                $query = "SELECT MAX(id) FROM member_info WHERE part=".$member_part;
                $stmt = $conn->prepare($query);
                $stmt->execute();

                $stmt->setFetchMode(PDO::FETCH_ASSOC);
                while($row = $stmt->fetch()) {

                    if(is_null($row['MAX(id)'])) {
                        $member_id = ($member_part * 10000) + 1;
                    } else {
                        $member_id = $row['MAX(id)'] + 1;
                    }
                    // echo $member_id;
                }

                $query = "INSERT INTO member_info (id, name, part, church_staff, calvary_staff, last_state) VALUES (:in1, :in2, :in3, :in4, :in5, 0)";
                $stmt = $conn->prepare($query);
                $stmt->bindParam(':in1', $in1);
                $stmt->bindParam(':in2', $in2);
                $stmt->bindParam(':in3', $in3);
                $stmt->bindParam(':in4', $in4);
                $stmt->bindParam(':in5', $in5);

                $in1 = $member_id;
                $in2 = $member_name;
                $in3 = $member_part;
                $in4 = $member_staff;
                $in5 = $calvary_staff;
                $stmt->execute();

                if($status_msg_code != "9012") {
                    $status_msg_code = "9011";
                }
            }
            catch(PDOException $e)
            {
                echo $query . "<br>" . $e->getMessage();
            }
        }
        unset($_POST['member_register']);

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
                    $additional_info='<table class="w3-table-all w3-hoverable"><tr><th>일련번호</th><th>이름</th><th>파트</th><th>직분</th></tr>';
                    while($row = $stmt->fetch()) {
                        $additional_info=$additional_info.'<tr>';
                        $additional_info=$additional_info.'<td>'.$row['id'].'</td>';
                        $additional_info=$additional_info.'<td>'.$row['name'].'</td>';
                        $additional_info=$additional_info.'<td>'.returnPartName($row['part']).'</td>';
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

                    $status_msg_code = "9016";
                } else if($num_of_rows == 0) {
                    //member not exist
                    $member_id = 0;
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
    } elseif (isset($_POST['member_update'])) {
        if(empty($member_id) || empty($member_name) || empty($member_part) || empty($member_staff) || empty($calvary_staff)) {
            //error
            $status_msg_code = "9017";
        } else {
            try {
                include_once 'dbconn.php';

                //update member information, get sn
                if(empty($member_sn)) {
                    //get sn to update member info
                    $query = "SELECT * FROM member_info WHERE part=".$member_part." AND name='".$member_name."' AND id=".$member_id;
                    $stmt = $conn->prepare($query);
                    $stmt->execute();
                    $num_of_rows = $stmt->rowCount();
                    // echo "num_of_rows: ".$num_of_rows."<br>";
                    if($num_of_rows == 1) {
                        $stmt->setFetchMode(PDO::FETCH_ASSOC);
                        while($row = $stmt->fetch()) {
                            $member_sn = $row['sn'];
                            // echo $member_sn;
                        }
                    } else if ($num_of_rows > 1) {
                        $status_msg_code = "9018";
                    } else {
                        $status_msg_code = "9019";
                    }

                } else {
                    //already got member info, try to update
                }

                if(empty($status_msg_code)){
                    $query = "UPDATE member_info SET id = :member_id, name = :member_name, part = :member_part, church_staff = :member_staff, calvary_staff = :member_calvary_staff WHERE sn = :member_sn";
                    $stmt = $conn->prepare($query);
                    $stmt->bindParam(':member_id', $member_id, PDO::PARAM_INT);
                    $stmt->bindParam(':member_name', $member_name, PDO::PARAM_STR);
                    $stmt->bindParam(':member_part', $member_part, PDO::PARAM_INT);
                    $stmt->bindParam(':member_staff', $member_staff, PDO::PARAM_INT);
                    $stmt->bindParam(':member_calvary_staff', $calvary_staff, PDO::PARAM_INT);
                    $stmt->bindParam(':member_sn', $member_sn, PDO::PARAM_INT);
                    $stmt->execute();

                    $status_msg_code = "9020"; //success
                }
            }
            catch(PDOException $e)
            {
                echo $query . "<br>" . $e->getMessage();
            }
        }
        unset($_POST['member_update']);
    } else {}

?>