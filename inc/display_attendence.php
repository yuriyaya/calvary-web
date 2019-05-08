<?php

    function displayTitleDescription($id) {
        $ret_str = '';

        if(is_null(checkAttLogDay($id))) {
            $ret_str = date('Y-m-d').' 오늘은 출석 입력일이 아닙니다.';
        } else {
            $ret_str = checkAttLogDay($id).' 출석 입력 중';
        }

        return $ret_str;
    }

    function checkAttLogDay($id) {
        $ret = '';

        include 'dbconn.php';

        if($id == 0) {
            $today = date('Y-m-d'); //check today is attendence day or not
            $query = "SELECT * FROM attendence_date WHERE att_date='".$today."'";
            $stmt = $conn->prepare($query);
            $stmt->execute();
            $num_of_rows = $stmt->rowCount();
            if($num_of_rows>0) {
                $ret = $today;
            } else {
                $ret = '';
            }
        } else {
            $query = "SELECT * FROM attendence_date WHERE sn=".$id;
            $stmt = $conn->prepare($query);
            $stmt->execute();
            $stmt->setFetchMode(PDO::FETCH_ASSOC);
            while($row = $stmt->fetch()) {
                $ret = $row['att_date'];
            }
        }

        return $ret;

    }

    function displayAttSearchOpt($id) {
        $ret_str = '';

        $ret_str = '<select class="w3-select w3-border" id="state" name="att_date_select">';
        if($id == 0) {
            $ret_str = $ret_str.'<option value="0" selected disabled>날짜를 선택하세요</option>';
        } else {
            $ret_str = $ret_str.'<option value="0" disabled>날짜를 선택하세요</option>';
        }

        include 'dbconn.php';
        $query = 'SELECT * FROM attendence_date ORDER BY att_date DESC LIMIT 10;';
        $stmt = $conn->prepare($query);
        $stmt->execute();
        $stmt->setFetchMode(PDO::FETCH_ASSOC);
        while($row = $stmt->fetch()) {
            if($id == $row['sn']) {
                $selected = " selected";
            } else {
                $selected = "";
            }
            $ret_str = $ret_str.'<option value="'.$row['sn'].'"'.$selected.'>'.$row['att_date'].'</option>';
        }
        $ret_str = $ret_str.'</select>';

        return $ret_str;
    }

    function dispalyAttLogHeader($date) {
        $ret_str = '';

        $one_month = strtotime($date.' -1 months');
        $two_month = strtotime($date.' -2 months');
        $three_month = strtotime($date.' -3 months');

        $ret_str = '<tr><th>이름</th><th>상태</th><th>출석</th><th>'.date('n', $one_month).'월</th><th>'.date('n', $two_month).'월</th><th>'.date('n', $three_month).'월</th><th>상태변경</th>';

        return $ret_str;
    }

    function displayAttendenceForm($part, $row_id, $date) {
        $att_check_form = '';

        $check_form = '<td><input class="w3-check" type="checkbox"></td>';
        $status_option = '<td><select class="w3-select w3-border" id="state" name="member_state">
            <option value="0" selected disabled>선택</option>
            <option value="1">정대원</option>
            <option value="6">휴식</option>
            <option value="7">제적</option>
        </select></td>';
        //display attendence check form
        $att_check_form='<table class="w3-table-all w3-hoverable" id="att_table" style="width:500px">'.dispalyAttLogHeader($date);
        $att_check_form=$att_check_form.'<tr><td>이름1<input type="hidden" name="id_10001" value="10001"></td><td></td>'.$check_form.'<td style="background-color:Tomato">100%</td><td>100%</td><td>100%</td>'.$status_option;
        $att_check_form=$att_check_form.'<tr><td>이름2<input type="hidden" name="id_10002" value="10002"></td><td></td>'.$check_form.'<td>100%</td><td>100%</td><td>100%</td>'.$status_option;
        $att_check_form=$att_check_form.'</table>';

        return $att_check_form;
    }
?>