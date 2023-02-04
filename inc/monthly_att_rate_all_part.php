<?php

    if(isset($_POST['att_date'])) {

        $stat_date_start = date('Y-m', strtotime($att_stat_date)).'-01';
        $stat_date_end = date('Y-m-t', strtotime($att_stat_date));
        // echo $stat_date_start.'  '.$stat_date_end.'<br>';

        $part_sum_count = array(0,0,0,0,0,0,0);
        
        //------------------------------ get total number of memeber
        $total_member_count = array(0,0,0,0,0,0,0);

        for($part_num=1; $part_num<8; $part_num++) {

            $att_list_ary = getMemberSNStateFromDB($part_num, $stat_date_end);
            $att_list_staff = $att_list_ary[0];
            $att_list_substaff = $att_list_ary[1];
            $att_list_normal = $att_list_ary[2];
            $att_list_newbie = $att_list_ary[3];

            $total_member_count[$part_num-1] += count($att_list_staff);
            $total_member_count[$part_num-1] += count($att_list_substaff);
            $total_member_count[$part_num-1] += count($att_list_normal);
            $total_member_count[$part_num-1] += count($att_list_newbie);

            $part_sum_count[$part_num-1] = getMemberAttSum($att_list_staff, $part_sum_count[$part_num-1], $stat_date_start, $stat_date_end);
            $part_sum_count[$part_num-1] = getMemberAttSum($att_list_substaff, $part_sum_count[$part_num-1], $stat_date_start, $stat_date_end);
            $part_sum_count[$part_num-1] = getMemberAttSum($att_list_normal, $part_sum_count[$part_num-1], $stat_date_start, $stat_date_end);
            $part_sum_count[$part_num-1] = getMemberAttSum($att_list_newbie, $part_sum_count[$part_num-1], $stat_date_start, $stat_date_end);

        }
        // echo json_encode($part_sum_count).'<br>';
        // echo json_encode($total_member_count).'<br>';

        $att_day_cnt = getAttLogDateMonthlyCountNumber($stat_date_end);
        // echo $att_day_cnt.'<br>';

        $str_html = '<table class="w3-table-all w3-hoverable" id="monthly_report_stat"><tr><th style="width:150px">파트</th><th>평균 출석율</th></tr>';

        for($part_num=1; $part_num<8; $part_num++) {
            $att_rate = round(($part_sum_count[$part_num-1]/($att_day_cnt*$total_member_count[$part_num-1]))*100);
            $str_html = $str_html.'<tr><td>'.returnPartName($part_num).'</td><td>'.$att_rate.'%</td></tr>';
        }

        $str_html = $str_html.'</table>';
        
    }

    function getMemberAttSum($mem_list, $sum, $start_date, $end_date) {

        include "dbconn.php";
        include_once "func_global.php";

        for($idx=0; $idx<count($mem_list); $idx++) {

            $one_member = $mem_list[$idx];
            $mem_id = $one_member[0];
            $mem_name = $one_member[1];
            $mem_state = $one_member[2];

            $query = "SELECT date, sum(attend_value) FROM ".getPartDBNameByMemberId($mem_id)." WHERE id=:in1 AND date IN (SELECT att_date FROM attendence_date WHERE type=:in2 AND att_date>='".$start_date."' AND att_date<='".$end_date."' ORDER BY att_date ASC) GROUP BY date;";

            $stmt = $conn->prepare($query);
            $stmt->bindParam(':in1', $in1);
            $stmt->bindParam(':in2', $in2);

            $in1 = $mem_id;
            $in2 = 0;
            $stmt->execute();
            $num_of_rows = $stmt->rowCount();

            if($num_of_rows > 0) {
                while($row = $stmt->fetch()) {
                    $date = (int)date('j', strtotime($row['date']));
                    // echo $date.'<br>';
                    $sum += (int)($row['sum(attend_value)']/10);
                }
            }

            $in2 = 6;
            $stmt->execute();
            $num_of_rows = $stmt->rowCount();

            if($num_of_rows > 0) {
                while($row = $stmt->fetch()) {
                    $date = (int)date('j', strtotime($row['date']));
                    // echo $date.'<br>';
                    $sum += (int)($row['sum(attend_value)']/10);
                }
            }

        }

        return $sum;
    }

?>