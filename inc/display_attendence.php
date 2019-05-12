<?php

    function displayTitleDescription($id) {
        $ret_str = '';

        if(empty(checkAttLogDayById($id))) {
            $ret_str = date('Y-m-d').' 오늘은 출석 입력일이 아닙니다.';
        } else {
            $ret_str = checkAttLogDayById($id).' 출석 입력 중';
        }

        return $ret_str;
    }

    function checkAttLogDayById($id) {
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

    function checkAttLogDayByDate($date) {
        $ret = '';

        include 'dbconn.php';

        $query = "SELECT * FROM attendence_date WHERE att_date='".$date."'";
        $stmt = $conn->prepare($query);
        $stmt->execute();
        $num_of_rows = $stmt->rowCount();
        if($num_of_rows>0) {
            $ret = $date;
        } else {
            $ret = '';
        }

        return $ret;

    }

    function displayAttSearchOpt($id) {
        $ret_str = '';

        $today = date('Y-m-d');
        $ret_str = '<select class="w3-select w3-border" id="state" name="att_date_select">';
        $ret_str = $ret_str.'<option value="0" disabled>날짜를 선택하세요</option>';

        include 'dbconn.php';
        $query = "SELECT * FROM attendence_date WHERE att_date <= '".$today."' ORDER BY att_date DESC LIMIT 10;";
        $stmt = $conn->prepare($query);
        $stmt->execute();
        $stmt->setFetchMode(PDO::FETCH_ASSOC);
        while($row = $stmt->fetch()) {
            if($id == $row['sn']) {
                $selected = " selected";
            } else if (($id == 0) && ($row['att_date'] == $today)){
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

    function displayAttendenceForm($part, $date) {

        $att_check_form = '';

        $att_list_ary = getMemberSNStateFromDB($part, $date);
        $att_list_staff = $att_list_ary[0];
        $att_list_normal = $att_list_ary[1];
        $att_list_newbie = $att_list_ary[2];
        $att_list_temp = $att_list_ary[3];
        $att_list_special = $att_list_ary[4];
        $att_list_pause = $att_list_ary[5];

        //display attendence check form
        $att_check_form='<table class="w3-table-all w3-hoverable" id="att_table" style="width:500px;">'.dispalyAttLogHeader($date);
        
        foreach($att_list_staff as $att_list) {
            // echo $att_list[0].'<br>'.$att_list[1].'<br>'.$att_list[2].'<br>';
            $att_check_form=$att_check_form.getAttOneRow($part, $date, $att_list[0], $att_list[1], $att_list[2], '파트장');
        }
        foreach($att_list_normal as $att_list) {
            // echo $att_list[0].'<br>'.$att_list[1].'<br>'.$att_list[2].'<br>';
            $att_check_form=$att_check_form.getAttOneRow($part, $date, $att_list[0], $att_list[1], $att_list[2]);
        }
        foreach($att_list_newbie as $att_list) {
            // echo $att_list[0].'<br>'.$att_list[1].'<br>'.$att_list[2].'<br>';
            $att_check_form=$att_check_form.getAttOneRow($part, $date, $att_list[0], $att_list[1], $att_list[2]);
        }
        foreach($att_list_temp as $att_list) {
            // echo $att_list[0].'<br>'.$att_list[1].'<br>'.$att_list[2].'<br>';
            $att_check_form=$att_check_form.getAttOneRow($part, $date, $att_list[0], $att_list[1], $att_list[2]);
        }
        foreach($att_list_special as $att_list) {
            // echo $att_list[0].'<br>'.$att_list[1].'<br>'.$att_list[2].'<br>';
            $att_check_form=$att_check_form.getAttOneRow($part, $date, $att_list[0], $att_list[1], $att_list[2]);
        }
        foreach($att_list_pause as $att_list) {
            // echo $att_list[0].'<br>'.$att_list[1].'<br>'.$att_list[2].'<br>';
            $att_check_form=$att_check_form.getAttOneRow($part, $date, $att_list[0], $att_list[1], $att_list[2]);
        }
        $att_check_form=$att_check_form.'</table>';

        return $att_check_form;
    }

    function getAttOneRow($part_num, $att_date, $mem_id, $mem_name, $mem_state, $staff_state=null) {
        $ret = '';

        $status_option = '<select class="w3-select w3-border" name="mem_state_up[]">
            <option value="0" selected>선택</option>
            <option value="1">정대원</option>
            <option value="6">휴식</option>
            <option value="7">제적</option>
        </select>';
        $check_form = getAttLogCheckbox($part_num, $att_date, $mem_id);

        $ret = $ret.'<tr>';
        $ret =  $ret.'<td>';
        $ret =   $ret.'<input type="hidden" name="mem_id[]" value="'.$mem_id.'">'.$mem_name.'<input type="hidden" name="mem_name[]" value="'.$mem_name.'">';
        $ret =  $ret.'</td>';
        $ret =  $ret.'<td>';
        $ret =   $ret.'<input type="hidden" name="mem_state[]" value="'.$mem_state.'">'.getAttendenceFormMemberState($mem_state, $staff_state);
        $ret =  $ret.'</td>';
        $ret =  $ret.'<td>'.$check_form.'</td>';
        $ret =  $ret.'<td>-%</td>';
        $ret =  $ret.'<td>-%</td>';
        $ret =  $ret.'<td>-%</td>';
        $ret =  $ret.'<td>'.$status_option.'</td>';
        $ret = $ret.'</tr>';

        return $ret;
    }

    function getAttLogCheckbox($part, $date, $id) {
        $ret = '';
        $att_result = '';

        include 'dbconn.php';
        $query = "SELECT * FROM ".getAttDBName($part)." WHERE date='".$date."' AND id=".$id.";";

        $stmt = $conn->prepare($query);
        $stmt->execute();
        $num_of_rows = $stmt->rowCount();

        if($num_of_rows > 0) {
            //already attendence log exist, update data
            $stmt->setFetchMode(PDO::FETCH_ASSOC);
            while($row = $stmt->fetch()) {
                if($row['attend_value']==10) {
                    $att_result = ' checked';
                }
            }
        }

        $ret = '<input type="hidden" name="mem_att_val[]" value="off"><input class="w3-check" type="checkbox" name="mem_att_val[]"'.$att_result.'>';

        return $ret;
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

    function getMemberSNStateFromDB($part, $date) {

        $ret_ary = array();

        $member_grp_staff = array();
        $member_grp_normal = array();
        $member_grp_newbie = array();
        $member_grp_temp = array();
        $member_grp_special = array();
        $member_grp_pause = array();
    
        $member_data = array();

        $part_min = $part*10000;
        $part_max = ($part+1)*10000;
        $id_prev = 0;

        include 'dbconn.php';
        $query = "SELECT * FROM member_info AS mi RIGHT JOIN member_state AS ms ON mi.id=ms.id WHERE ms.state_update_date<'".$date."' AND mi.id>".$part_min." AND mi.id<".$part_max." ORDER BY mi.name ASC, ms.state_update_date DESC;";
        $stmt = $conn->prepare($query);
        $stmt->execute();
        $stmt->setFetchMode(PDO::FETCH_ASSOC);
        while($row = $stmt->fetch()) {
            if($row['id'] != $id_prev) {
                $member_data = array($row['id'], $row['name'], $row['state']);
                if($row['calvary_staff'] == 2) { //calvary staff 파트장
                    array_push($member_grp_staff, $member_data);
                } else if(($row['calvary_staff'] < 2) && ($row['state'] <= 2)) { //정대원
                    array_push($member_grp_normal, $member_data);
                } else if(($row['calvary_staff'] < 2) && ($row['state'] == 3)) { //신입
                    array_push($member_grp_newbie, $member_data);
                } else if(($row['calvary_staff'] < 2) && ($row['state'] == 4)) { //임시
                    array_push($member_grp_temp, $member_data);
                } else if(($row['calvary_staff'] < 2) && ($row['state'] == 5)) { //특별
                    array_push($member_grp_special, $member_data);
                } else if(($row['calvary_staff'] < 2) && ($row['state'] == 6)) { //휴식
                    array_push($member_grp_pause, $member_data);
                } else {
                    //do not display in attendence form
                }
            } else {
                //same id, ignore data
            }
            $id_prev = $row['id'];
        }
        // echo '<br>파트장----------------<br>';
        // echo json_encode($member_grp_staff, JSON_UNESCAPED_UNICODE);
        // echo '<br>정대원+솔리스트----------------<br>';
        // echo json_encode($member_grp_normal, JSON_UNESCAPED_UNICODE);
        // echo '<br>신입----------------<br>';
        // echo json_encode($member_grp_newbie, JSON_UNESCAPED_UNICODE);
        // echo '<br>임시----------------<br>';
        // echo json_encode($member_grp_temp, JSON_UNESCAPED_UNICODE);
        // echo '<br>특별----------------<br>';
        // echo json_encode($member_grp_special, JSON_UNESCAPED_UNICODE);
        // echo '<br>휴식----------------<br>';
        // echo json_encode($member_grp_pause, JSON_UNESCAPED_UNICODE);
        // echo '<br>----------------<br>';

        array_push($ret_ary, $member_grp_staff);
        array_push($ret_ary, $member_grp_normal);
        array_push($ret_ary, $member_grp_newbie);
        array_push($ret_ary, $member_grp_temp);
        array_push($ret_ary, $member_grp_special);
        array_push($ret_ary, $member_grp_pause);
        return $ret_ary;
    }

    function getAttendenceFormMemberState($member_state_num, $member_staff=null) {
        $ret_str = '';

        if(!is_null($member_staff)) {
            $ret_str = $member_staff;
        } else {
            switch($member_state_num){
                case 1:
                case 2:
                    $ret_str = "";
                    break;
                case 3:
                    $ret_str = "신입";
                    break;
                case 4:
                    $ret_str = "임시";
                    break;
                case 5:
                    $ret_str = "특별";
                    break;
                case 6:
                    $ret_str = "휴식";
                    break;
                default:
                    $ret_str = "오류";
                    break;
            }
        }

        return $ret_str;
    }
?>