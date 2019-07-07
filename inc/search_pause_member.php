<?php

    function displayPauseMember($part) {

        $ret = '<table class="w3-table-all w3-hoverable"><tr><th>파트</th><th>이름</th><th>휴식일</th><th>경과일</th></tr>';

        include_once 'dbconn.php';

        $query = "SELECT * FROM member_info WHERE part=".$part." AND last_state=6 ORDER BY name ASC";
        $stmt = $conn->prepare($query);
        $stmt->execute();
        $num_of_rows = $stmt->rowCount();
        // echo "num_of_rows: ".$num_of_rows."<br>";
        if($num_of_rows==0) {
            $status_msg_code = "8061";
        } else {
            while($row = $stmt->fetch()) {
                $ret = $ret.'<tr><td>'.returnPartName($part).'</td><td>'.$row['name'].'</td>';

                $query_pause = "SELECT * FROM member_state WHERE id=".$row['id']." AND state=6 ORDER BY state_update_date DESC LIMIT 1";
                $stmt_pause = $conn->prepare($query_pause);
                $stmt_pause->execute();
                while($row_pause = $stmt_pause->fetch()) {
                    // $change_date = date('Y-m-d', strtotime($row_pause['state_update_date']));
                    $change_date = date_create($row_pause['state_update_date']);
                    $today = date_create(date('Y-m-d'));
                    $diff=date_diff($change_date, $today);
                    $diff_str = $diff->format("%a");
                    if((int)$diff_str > 365) {
                        $color_code = ' class="w3-red"';
                    } else {
                        $color_code = '';
                    }
                    $ret = $ret.'<td>'.$row_pause['state_update_date'].'</td><td'.$color_code.'>'.$diff_str.'</td></tr>';
                }
                
            }
        }

        $ret = $ret.'</table>';

        return $ret;
    }

?>