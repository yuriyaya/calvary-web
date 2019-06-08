<?php
    $date = date('Y-m-d');

    include 'dbconn.php';

    $result_html = '<table class="w3-table-all w3-hoverable">'.dispalyAttLogYearHeader();

    $newbie_list = array();

    $query_newbie = "SELECT id, name, part, last_state FROM member_info WHERE last_state=3 ORDER BY part;";
    $stmt_newbie = $conn->prepare($query_newbie);
    $stmt_newbie->execute();
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
?>