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

        $ret_str = '<tr><th>이름</th><th>상태</th><th>출석</th><th>'.date('n', $one_month).'월</th><th>'.date('n', $two_month).'월</th><th>'.date('n', $three_month).'월</th></tr>';

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

        $att_check_form=$att_check_form.getAttOneRowBind($part, $date, $att_list_staff, '파트장');
        $att_check_form=$att_check_form.getAttOneRowBind($part, $date, $att_list_normal);
        $att_check_form=$att_check_form.getAttOneRowBind($part, $date, $att_list_newbie);
        $att_check_form=$att_check_form.getAttOneRowBind($part, $date, $att_list_temp);
        $att_check_form=$att_check_form.getAttOneRowBind($part, $date, $att_list_special);
        $att_check_form=$att_check_form.getAttOneRowBind($part, $date, $att_list_pause);

        $att_check_form=$att_check_form.'</table>';

        return $att_check_form;
    }

    function getAttOneRowBind($part_num, $att_date, $mem_list, $staff_state=null) {
        $ret = '';

        $month_start = date('Y-m', strtotime($att_date.' -3 months')).'-01';
        $month_end = date('Y-m-t', strtotime($att_date.' -1 months'));

        include 'dbconn.php';
        for($idx=0; $idx<count($mem_list); $idx++) {
            $one_member = $mem_list[$idx];
            $mem_id = $one_member[0];
            $mem_name = $one_member[1];
            $mem_state = $one_member[2];

            //attendence state, check box
            $query = "SELECT * FROM ".getAttDBName($part_num)." WHERE date=:in1 AND id=:in2;";

            $stmt = $conn->prepare($query);
            $stmt->bindParam(':in1', $in1);
            $stmt->bindParam(':in2', $in2);
            $in1 = $att_date;
            $in2 = $one_member[0];
            $stmt->execute();
            $num_of_rows = $stmt->rowCount();

            $checked = '';
            if($num_of_rows > 0) {
                //already attendence log exist, update data
                $stmt->setFetchMode(PDO::FETCH_ASSOC);
                while($row = $stmt->fetch()) {
                    if($row['attend_value']==10) {
                        $checked = ' checked';
                    }
                }
            }

            //previous 3 month attendence rate
            $monthly_att_rate_ary = array();

            $one_member = $mem_list[$idx];
            $mem_id = $one_member[0];
            $mem_name = $one_member[1];
            $mem_state = $one_member[2];

            $query_month = "SELECT * FROM ".getAttMonthlyDBName($part_num)." WHERE date>=:in1 AND date<=:in2 AND id=:in3 ORDER BY date DESC;";

            $stmt_month = $conn->prepare($query_month);
            $stmt_month->bindParam(':in1', $in1);
            $stmt_month->bindParam(':in2', $in2);
            $stmt_month->bindParam(':in3', $in3);
            $in1 = $month_start;
            $in2 = $month_end;
            $in3 = $one_member[0];
            $stmt_month->execute();
            $num_of_rows_month = $stmt_month->rowCount();

            if($num_of_rows_month > 0) {
                $stmt_month->setFetchMode(PDO::FETCH_ASSOC);
                while($row = $stmt_month->fetch()) {
                    array_push($monthly_att_rate_ary, $row['att_month_rate']);
                }
            }

            $check_form = '<input type="hidden" name="mem_att_val[]" value="off"><input class="w3-check" type="checkbox" name="mem_att_val[]"'.$checked.'>';

            $ret = $ret.'<tr>';
            $ret =  $ret.'<td>';
            $ret =   $ret.'<input type="hidden" name="mem_id[]" value="'.$mem_id.'">'.$mem_name.'<input type="hidden" name="mem_name[]" value="'.$mem_name.'">';
            $ret =  $ret.'</td>';
            $ret =  $ret.'<td>';
            $ret =   $ret.'<input type="hidden" name="mem_state[]" value="'.$mem_state.'">'.getAttendenceFormMemberState($mem_state, $staff_state);
            $ret =  $ret.'</td>';
            $ret =  $ret.'<td>'.$check_form.'</td>';
            if(array_key_exists(0, $monthly_att_rate_ary)){
                $ret =  $ret.'<td'.getBGColorHTML($monthly_att_rate_ary[0]).'>'.$monthly_att_rate_ary[0].'%</td>';
            } else {
                $ret =  $ret.'<td>-%</td>';
            }
            if(array_key_exists(1, $monthly_att_rate_ary)){
                $ret =  $ret.'<td'.getBGColorHTML($monthly_att_rate_ary[1]).'>'.$monthly_att_rate_ary[1].'%</td>';
            } else {
                $ret =  $ret.'<td>-%</td>';
            }
            if(array_key_exists(2, $monthly_att_rate_ary)){
                $ret =  $ret.'<td'.getBGColorHTML($monthly_att_rate_ary[2]).'>'.$monthly_att_rate_ary[2].'%</td>';
            } else {
                $ret =  $ret.'<td>-%</td>';
            }
            $ret = $ret.'</tr>';

        }

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
        $query = "SELECT * FROM member_info AS mi RIGHT JOIN member_state AS ms ON mi.id=ms.id WHERE ms.state_update_date<='".$date."' AND mi.id>".$part_min." AND mi.id<".$part_max." ORDER BY mi.name ASC, ms.state_update_date DESC;";
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

    function displayAttendenceMonthlyForm($part, $date) {

        $att_check_form = '';

        $last_date = date('Y-m-t', strtotime($date));

        $att_list_ary = getMemberSNStateFromDB($part, $last_date);
        $att_list_staff = $att_list_ary[0];
        $att_list_normal = $att_list_ary[1];
        $att_list_newbie = $att_list_ary[2];
        $att_list_temp = $att_list_ary[3];
        $att_list_special = $att_list_ary[4];
        $att_list_pause = $att_list_ary[5];

        $date_list_ary = getAttLogDateArray($date);

        //display attendence check form
        $att_check_form='<table class="w3-table-all w3-hoverable" id="att_table" style="width:700px">'.dispalyAttLogMonthlyHeader($date);

        $att_check_form=$att_check_form.getAttMonthlyOneRowBind($part, $date, $att_list_staff, $date_list_ary, '파트장');
        $att_check_form=$att_check_form.getAttMonthlyOneRowBind($part, $date, $att_list_normal, $date_list_ary);
        $att_check_form=$att_check_form.getAttMonthlyOneRowBind($part, $date, $att_list_newbie, $date_list_ary);
        $att_check_form=$att_check_form.getAttMonthlyOneRowBind($part, $date, $att_list_temp, $date_list_ary);
        $att_check_form=$att_check_form.getAttMonthlyOneRowBind($part, $date, $att_list_special, $date_list_ary);
        $att_check_form=$att_check_form.getAttMonthlyOneRowBind($part, $date, $att_list_pause, $date_list_ary);

        $att_check_form=$att_check_form.'</table>';

        return $att_check_form;
    }

    function dispalyAttLogMonthlyHeader($date) {
        $ret_str = '<tr><th>이름</th><th>상태</th>';

        $att_log_ary = getAttLogDateArray($date);

        $ary_temp = $att_log_ary[0]; //saturday
        for($idx=0; $idx<count($ary_temp); $idx++) {
            $ret_str = $ret_str.'<th>'.date('d', strtotime($ary_temp[$idx])).'</th>';
        }

        $ary_temp = $att_log_ary[1]; //sunday
        for($idx=0; $idx<count($ary_temp); $idx++) {
            $ret_str = $ret_str.'<th>'.date('d', strtotime($ary_temp[$idx])).'</th>';
        }

        $ary_temp = $att_log_ary[2]; //other day
        for($idx=0; $idx<count($ary_temp); $idx++) {
            $ret_str = $ret_str.'<th>'.date('d', strtotime($ary_temp[$idx])).'</th>';
        }

        $ret_str = $ret_str.'<th>토</th><th>일</th><th>합계</th><th>%</th></tr>';

        return $ret_str;
    }

    function getAttLogDateArray($date) {
        $ret_ary = array();

        $ary_sat = array();
        $ary_sun = array();
        $ary_others = array();

        $month_start = date('Y-m', strtotime($date)).'-01';
        $month_end = date('Y-m-t', strtotime($date));
        // echo $month_start.'<br>';
        // echo $month_end.'<br>';

        include 'dbconn.php';
        $query = "SELECT * FROM attendence_date WHERE att_date >='".$month_start."' AND att_date <='".$month_end."' ORDER BY att_date ASC;";
        $stmt = $conn->prepare($query);
        $stmt->execute();
        $stmt->setFetchMode(PDO::FETCH_ASSOC);
        while($row = $stmt->fetch()) {
            $day = date('w', strtotime($row['att_date']));
            if($day == 0) {
                //sunday
                array_push($ary_sun, $row['att_date']);
            } else if ($day == 6) {
                //saturday
                array_push($ary_sat, $row['att_date']);
            } else {
                //others
                array_push($ary_others, $row['att_date']);
            }
        }
        // echo '<br>----------------<br>';
        // echo json_encode($ary_sat, JSON_UNESCAPED_UNICODE);
        // echo '<br>----------------<br>';
        // echo json_encode($ary_sun, JSON_UNESCAPED_UNICODE);
        // echo '<br>----------------<br>';
        // echo json_encode($ary_others, JSON_UNESCAPED_UNICODE);
        // echo '<br>----------------<br>';

        $ret_ary = array($ary_sat, $ary_sun, $ary_others);

        return $ret_ary;
    }

    function getAttMonthlyOneRowBind($part_num, $date, $mem_list, $att_date_list, $staff_state=null) {
        $ret = '';
        $att_val_ary = array(0, 0, 0); //idx=0 : saturday, idx=1 : sunday, idx=2 : other day
        $day_total_ary = array(0, 0, 0);
        $month_start = date('Y-m', strtotime($date)).'-01';
        $month_end = date('Y-m-t', strtotime($date));

        // echo json_encode($att_date_list[0]);
        // echo '<br>';
        // echo json_encode($att_date_list[1]);
        // echo '<br>';
        // echo json_encode($att_date_list[2]);
        // echo '<br>';

        for($idx_day=0; $idx_day<3; $idx_day++) {
            $day_total_ary[$idx_day] = count($att_date_list[$idx_day]);
        }

        include 'dbconn.php';

        for($idx=0; $idx<count($mem_list); $idx++) {

            $att_val_sat_ary = array_fill(0, $day_total_ary[0], 0);
            $att_val_sun_ary = array_fill(0, $day_total_ary[1], 0);
            $att_val_other_ary = array_fill(0, $day_total_ary[2], 0);

            // echo json_encode($att_val_sat_ary);
            // echo '<br>';
            // echo json_encode($att_val_sun_ary);
            // echo '<br>';
            // echo json_encode($att_val_other_ary);
            // echo '<br>';

            $one_member = $mem_list[$idx];
            $mem_id = $one_member[0];
            $mem_name = $one_member[1];
            $mem_state = $one_member[2];

            $ret = $ret.'<tr>';
            $ret =  $ret.'<td><input type="hidden" name="mem_id[]" value="'.$mem_id.'">'.$mem_name.'</td>';
            $ret =  $ret.'<td>'.getAttendenceFormMemberState($mem_state, $staff_state).'</td>';

            $query = "SELECT * FROM ".getAttDBName($part_num)." WHERE date>=:in1 AND date<=:in2 AND id=:in3 ORDER BY date ASC;";

            $stmt = $conn->prepare($query);
            $stmt->bindParam(':in1', $in1);
            $stmt->bindParam(':in2', $in2);
            $stmt->bindParam(':in3', $in3);
            $in1 = $month_start;
            $in2 = $month_end;
            $in3 = $one_member[0];
            $stmt->execute();
            $num_of_rows = $stmt->rowCount();

            $att_val=0;
            if($num_of_rows > 0) {
                $stmt->setFetchMode(PDO::FETCH_ASSOC);
                while($row = $stmt->fetch()) {
                    if($row['attend_value']==10) {
                        $att_val = 1;
                    } else {
                        $att_val = 0;
                    }
                    // echo $row['date'].'<br>';
                    $key_sat = array_search($row['date'], $att_date_list[0]);
                    $key_sun = array_search($row['date'], $att_date_list[1]);
                    $key_other = array_search($row['date'], $att_date_list[2]);
                    // echo $key_sat.'/'.$key_sun.'/'.$key_other.'<br>';
                    if($key_sat !== false) {
                        // echo 'add val to sat '.$key_sat.', val='.$att_val.'<br>';
                        $att_val_sat_ary[$key_sat] = $att_val;
                    } else if($key_sun !== false) {
                        // echo 'add val to sun '.$key_sun.', val='.$att_val.'<br>';
                        $att_val_sun_ary[$key_sun] = $att_val;
                    } else if($key_other !== false) {
                        // echo 'add val to other '.$key_other.', val='.$att_val.'<br>';
                        $att_val_other_ary[$key_other] = $att_val;
                    } else {
                        //
                    }
                }
            }

            // echo json_encode($att_val_sat_ary);
            // echo '<br>';
            // echo json_encode($att_val_sun_ary);
            // echo '<br>';
            // echo json_encode($att_val_other_ary);
            // echo '<br>';

            for($idx_date=0; $idx_date<$day_total_ary[0]; $idx_date++) {
                $ret = $ret.'<td>'.$att_val_sat_ary[$idx_date].'</td>';
            }
            for($idx_date=0; $idx_date<$day_total_ary[1]; $idx_date++) {
                $ret = $ret.'<td>'.$att_val_sun_ary[$idx_date].'</td>';
            }
            for($idx_date=0; $idx_date<$day_total_ary[2]; $idx_date++) {
                $ret = $ret.'<td>'.$att_val_other_ary[$idx_date].'</td>';
            }
            $att_val_sum = 0;
            if($day_total_ary[0]>0) {
                $att_val_sum = $att_val_sum + array_sum($att_val_sat_ary);
                $ret = $ret.'<td>'.array_sum($att_val_sat_ary).'</td>';
            }
            if($day_total_ary[1]>0) {
                $att_val_sum = $att_val_sum + array_sum($att_val_sun_ary);
                $ret = $ret.'<td>'.array_sum($att_val_sun_ary).'</td>';
            }
            if($day_total_ary[2]>0) {
                $att_val_sum = $att_val_sum + array_sum($att_val_other_ary);
                $ret = $ret.'<td>'.array_sum($att_val_other_ary).'</td>';
            }
            $ret = $ret.'<td>'.$att_val_sum.'</td>';
            $att_rate = (int)(($att_val_sum/array_sum($day_total_ary))*100);
            $ret =  $ret.'<td'.getBGColorHTML($att_rate).'><input type="hidden" name="att_month_rate[]" value="'.$att_rate.'">'.$att_rate.'%</td>';
            
        }
        $ret = $ret.'</tr>';

        return $ret;
    }

    function displayAttStatChangeForm($part, $date) {

        $att_check_form = '';

        $att_list_ary = getMemberSNStateFromDB($part, $date);
        $att_list_staff = $att_list_ary[0];
        $att_list_normal = $att_list_ary[1];
        $att_list_newbie = $att_list_ary[2];
        $att_list_temp = $att_list_ary[3];
        $att_list_special = $att_list_ary[4];
        $att_list_pause = $att_list_ary[5];

        //display attendence check form
        $att_check_form='<table class="w3-table-all w3-hoverable" id="att_table">'.displayAttStatChangeFormHeader($date);
        
        $att_check_form=$att_check_form.getAttStatChangeOneRowBind($part, $date, $att_list_staff, '파트장');
        $att_check_form=$att_check_form.getAttStatChangeOneRowBind($part, $date, $att_list_normal);
        $att_check_form=$att_check_form.getAttStatChangeOneRowBind($part, $date, $att_list_newbie);
        $att_check_form=$att_check_form.getAttStatChangeOneRowBind($part, $date, $att_list_temp);
        $att_check_form=$att_check_form.getAttStatChangeOneRowBind($part, $date, $att_list_special);
        $att_check_form=$att_check_form.getAttStatChangeOneRowBind($part, $date, $att_list_pause);
        $att_check_form=$att_check_form.'</table>';

        return $att_check_form;
    }

    function displayAttStatChangeFormHeader() {
        $ret_str = '';

        $one_month = strtotime(date('Y-m-d').' -1 months');
        $two_month = strtotime(date('Y-m-d').' -2 months');
        $three_month = strtotime(date('Y-m-d').' -3 months');

        $ret_str = '<tr><th>이름</th><th>상태</th><th>'.date('n', $one_month).'월</th><th>'.date('n', $two_month).'월</th><th>'.date('n', $three_month).'월</th><th>상태변경</th>';

        return $ret_str;
    }

    function getAttStatChangeOneRowBind($part_num, $date, $mem_list, $staff_state=null) {
        $ret = '';

        $status_option = '<select class="w3-select w3-border" id="state" name="mem_state_up[]">
            <option value="0" selected>선택</option>
            <option value="1">정대원</option>
            <option value="2">솔리스트</option>
            <option value="3">신입</option>
            <option value="4">임시</option>
            <option value="5">특별</option>
            <option value="6">휴식</option>
            <option value="7">제적</option>
            <option value="8">은퇴</option>
            <option value="9">명예</option>
        </select>';
        $month_start = date('Y-m', strtotime($date.' -3 months')).'-01';
        $month_end = date('Y-m-t', strtotime($date.' -1 months'));

        include 'dbconn.php';
        for($idx=0; $idx<count($mem_list); $idx++) {
            $monthly_att_rate_ary = array();

            $one_member = $mem_list[$idx];
            $mem_id = $one_member[0];
            $mem_name = $one_member[1];
            $mem_state = $one_member[2];

            $query = "SELECT * FROM ".getAttMonthlyDBName($part_num)." WHERE date>=:in1 AND date<=:in2 AND id=:in3 ORDER BY date DESC;";

            $stmt = $conn->prepare($query);
            $stmt->bindParam(':in1', $in1);
            $stmt->bindParam(':in2', $in2);
            $stmt->bindParam(':in3', $in3);
            $in1 = $month_start;
            $in2 = $month_end;
            $in3 = $one_member[0];
            $stmt->execute();
            $num_of_rows = $stmt->rowCount();

            if($num_of_rows > 0) {
                $stmt->setFetchMode(PDO::FETCH_ASSOC);
                while($row = $stmt->fetch()) {
                    array_push($monthly_att_rate_ary, $row['att_month_rate']);
                }
            }
            $ret = $ret.'<tr>';
            $ret =  $ret.'<td>';
            $ret =   $ret.'<input type="hidden" name="mem_id[]" value="'.$mem_id.'">'.$mem_name.'';
            $ret =  $ret.'</td>';
            $ret =  $ret.'<td>';
            $ret =   $ret.'<input type="hidden" name="mem_state[]" value="'.$mem_state.'">'.getAttendenceFormMemberState($mem_state, $staff_state);
            $ret =  $ret.'</td>';
            if(array_key_exists(0, $monthly_att_rate_ary)){
                $ret =  $ret.'<td'.getBGColorHTML($monthly_att_rate_ary[0]).'>'.$monthly_att_rate_ary[0].'%</td>';
            } else {
                $ret =  $ret.'<td>-%</td>';
            }
            if(array_key_exists(1, $monthly_att_rate_ary)){
                $ret =  $ret.'<td'.getBGColorHTML($monthly_att_rate_ary[1]).'>'.$monthly_att_rate_ary[1].'%</td>';
            } else {
                $ret =  $ret.'<td>-%</td>';
            }
            if(array_key_exists(2, $monthly_att_rate_ary)){
                $ret =  $ret.'<td'.getBGColorHTML($monthly_att_rate_ary[2]).'>'.$monthly_att_rate_ary[2].'%</td>';
            } else {
                $ret =  $ret.'<td>-%</td>';
            }
            $ret =  $ret.'<td>'.$status_option.'</td>';
            $ret = $ret.'</tr>';

            
        }

        return $ret;
    }

    function getBGColorHTML($value) {
        $ret = '';
        if($value < 50) {
            $ret = ' style="background-color:Tomato"';
        }
        return $ret;
    }

    function get3monthAttRate($part_num, $mem_id, $date){
        $ret_ary = array();

        $month_start = date('Y-m', strtotime($date.' -3 months')).'-01';
        $month_end = date('Y-m-t', strtotime($date.' -1 months'));

        include 'dbconn.php';
        $query = "SELECT * FROM ".getAttMonthlyDBName($part_num)." WHERE date>='".$month_start."' AND date<='".$month_end."' AND id=".$mem_id." ORDER BY date DESC;";

        $stmt = $conn->prepare($query);
        $stmt->execute();
        $num_of_rows = $stmt->rowCount();
        // echo $query.'<br>';

        if($num_of_rows > 0) {
            //already attendence log exist, update data
            $stmt->setFetchMode(PDO::FETCH_ASSOC);
            while($row = $stmt->fetch()) {
                array_push($ret_ary, $row['att_month_rate']);
            }
        }

        return $ret_ary;
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

    function displayAttendenceYearForm($part, $date) {

        $att_year_form = '';

        $last_date = date('Y-m-t', strtotime($date.' -1 months'));

        $att_list_ary = getMemberSNStateFromDB($part, $last_date);
        $att_list_staff = $att_list_ary[0];
        $att_list_normal = $att_list_ary[1];
        $att_list_newbie = $att_list_ary[2];
        $att_list_temp = $att_list_ary[3];
        $att_list_special = $att_list_ary[4];
        $att_list_pause = $att_list_ary[5];

        //display attendence check form
        $att_year_form='<table class="w3-table-all w3-hoverable" id="att_table" style="width:700px">'.dispalyAttLogYearHeader();

        $att_year_form=$att_year_form.getAttYearOneRowBind($part, $date, $att_list_staff, '파트장');
        $att_year_form=$att_year_form.getAttYearOneRowBind($part, $date, $att_list_normal);
        $att_year_form=$att_year_form.getAttYearOneRowBind($part, $date, $att_list_newbie);
        $att_year_form=$att_year_form.getAttYearOneRowBind($part, $date, $att_list_temp);
        $att_year_form=$att_year_form.getAttYearOneRowBind($part, $date, $att_list_special);
        $att_year_form=$att_year_form.getAttYearOneRowBind($part, $date, $att_list_pause);

        $att_year_form=$att_year_form.'</table>';

        return $att_year_form;
    }

    function dispalyAttLogYearHeader() {

        $ret_str = '<tr><th>이름</th><th>상태</th>';
    
        $ret_str = $ret_str.'<th>1월</th>';
        $ret_str = $ret_str.'<th>2월</th>';
        $ret_str = $ret_str.'<th>3월</th>';
        $ret_str = $ret_str.'<th>4월</th>';
        $ret_str = $ret_str.'<th>5월</th>';
        $ret_str = $ret_str.'<th>6월</th>';
        $ret_str = $ret_str.'<th>7월</th>';
        $ret_str = $ret_str.'<th>8월</th>';
        $ret_str = $ret_str.'<th>9월</th>';
        $ret_str = $ret_str.'<th>10월</th>';
        $ret_str = $ret_str.'<th>11월</th>';
        $ret_str = $ret_str.'<th>12월</th>';

        $ret_str = $ret_str.'<th>평균(%)</th></tr>';

        return $ret_str;
    }

    function getAttYearOneRowBind($part_num, $date, $mem_list, $staff_state=null) {
        $ret = '';

        $year_start = date('Y', strtotime($date)).'-01-01'; //first day or year
        $year_end = date('Y', strtotime($date)).'-12-31'; //last day of year
        // echo $year_start.'<br>';
        // echo $year_end.'<br>';

        include 'dbconn.php';

        for($idx=0; $idx<count($mem_list); $idx++) {

            $att_rate_ary = array_fill(0, 12, 0);

            $one_member = $mem_list[$idx];
            $mem_id = $one_member[0];
            $mem_name = $one_member[1];
            $mem_state = $one_member[2];

            $ret = $ret.'<tr>';
            $ret =  $ret.'<td><input type="hidden" name="mem_id[]" value="'.$mem_id.'">'.$mem_name.'</td>';
            $ret =  $ret.'<td>'.getAttendenceFormMemberState($mem_state, $staff_state).'</td>';

            $query = "SELECT * FROM ".getAttMonthlyDBName($part_num)." WHERE date>=:in1 AND date<=:in2 AND id=:in3 ORDER BY date ASC;";

            $stmt = $conn->prepare($query);
            $stmt->bindParam(':in1', $in1);
            $stmt->bindParam(':in2', $in2);
            $stmt->bindParam(':in3', $in3);
            $in1 = $year_start;
            $in2 = $year_end;
            $in3 = $one_member[0];
            $stmt->execute();
            $num_of_rows = $stmt->rowCount();

            $att_val=0;
            if($num_of_rows > 0) {
                $stmt->setFetchMode(PDO::FETCH_ASSOC);
                while($row = $stmt->fetch()) {
                    $att_date = $row['date'];
                    $month = (int)(date('n', strtotime($att_date)));
                    // echo $month.'<br>';
                    $att_rate_ary[$month-1] = $row['att_month_rate'];
                }
            }

            // echo json_encode($att_rate_ary);
            // echo '<br>';

            for($idx_month=0; $idx_month<12; $idx_month++) {
                if(($att_rate_ary[$idx_month] < 50) && ($att_rate_ary[$idx_month] > 0)) {
                    $ret = $ret.'<td style="background-color:Tomato">'.$att_rate_ary[$idx_month].'</td>';
                } else if($att_rate_ary[$idx_month] == 100) {
                    $ret = $ret.'<td style="background-color:MediumSeaGreen">'.$att_rate_ary[$idx_month].'</td>';
                } else {
                    $ret = $ret.'<td>'.$att_rate_ary[$idx_month].'</td>';
                }
            }
            $avg_att_rate = (int)(array_sum($att_rate_ary)/(int)date('n', strtotime($date.' -1 months')));
            $ret =  $ret.'<td'.getBGColorHTML($avg_att_rate).'>'.$avg_att_rate.'%</td>';
            
        }
        $ret = $ret.'</tr>';

        return $ret;
    }

?>