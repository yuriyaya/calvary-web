<?php

    $error = "";
    $member_id = 0;

    if(isset($_POST['member_register'])) {

        if(empty($member_name) || empty($member_part) || empty($member_join_date) || empty($member_staff)) {
            //error
            $error = "member_input_empty";
        } else {
            try {
                include_once 'dbconn.php';

                $member_name = $_POST['member_name'];
                $member_part = $_POST['member_part'];
                $member_join_date = $_POST['member_join_date'];
                $member_staff = $_POST['member_staff'];
                // echo $member_name.'<br>';
                // echo $member_part.'<br>';
                // echo $member_join_date.'<br>';
                // echo $member_staff.'<br>';

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

                $query = "INSERT INTO member_info (id, name, part, join_date, church_staff) VALUES (:in1, :in2, :in3, :in4, :in5)";
                $stmt = $conn->prepare($query);
                $stmt->bindParam(':in1', $in1);
                $stmt->bindParam(':in2', $in2);
                $stmt->bindParam(':in3', $in3);
                $stmt->bindParam(':in4', $in4);
                $stmt->bindParam(':in5', $in5);

                $in1 = $member_id;
                $in2 = $member_name;
                $in3 = $member_part;
                $in4 = $member_join_date;
                $in5 = $member_staff;
                $stmt->execute();

                $error = "member_register_success";
                
            }
            catch(PDOException $e)
            {
                echo $query . "<br>" . $e->getMessage();
            }
        }
        unset($_POST['member_register']);
    } else {
        
    }


    // $stmt=$db->prepare(“INSERT INTO test (name) VALUES (:col2)”); 
    // // 첫번째열은 auto_increment 이므로 삽입할 필요가 없다.
    // $stmt->bindParam(‘:col2′,$data2);
    // $data2=”Kelvin”;
    // $stmt->execute();

?>