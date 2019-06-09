<?php
    $date = date('Y-m-d');

    include 'dbconn.php';
    $query_newbie = "SELECT id, name, part, last_state FROM member_info WHERE last_state=3 ORDER BY part;";
    $stmt_newbie = $conn->prepare($query_newbie);
    $stmt_newbie->execute();
    $num_of_rows = $stmt_newbie->rowCount();

    if($num_of_rows > 0) {
        $result_html = '<table class="w3-table-all w3-hoverable" style="width:700px">'.dispalyAttLogYearHeader();

        $newbie_list = array();

        $stmt_newbie->setFetchMode(PDO::FETCH_ASSOC);
        while($row = $stmt_newbie->fetch()) {
            $newbie_one = array($row['id'], $row['name'], $row['last_state']);
            array_push($newbie_list, $newbie_one);
        }

        // echo json_encode($newbie_list);

        for($idx=0; $idx<count($newbie_list); $idx++) {
            $one_member = $newbie_list[$idx];
            $part_num = $part_num = (int)($one_member[0]/10000);
            $newbie_temp = array();
            array_push($newbie_temp, $one_member);
            $result_html = $result_html.getAttYearOneRowBind($part_num, $date, $newbie_temp);
        }

        $result_html = $result_html.'</table>';
    } else {
        echo '<div class="w3-panel w3-red">
            <h3>신입대원 없음</h3>
            <p>현재 신입 대원이 없습니다.</p>
        </div>';
    }

?>