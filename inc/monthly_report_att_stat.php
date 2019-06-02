<?php

    if(isset($_POST['report_att_stat_date'])){$att_stat_date = $_POST['report_att_stat_date'];}

    if(isset($_POST['report_att_stat_date'])) {

        $stat_date_start = date('Y-m', strtotime($att_stat_date)).'-01';
        $stat_date_end = date('Y-m-t', strtotime($att_stat_date));
        // echo $stat_date_start.'  '.$stat_date_end.'<br>';

        $sop_sum = array();
        $alto_sum = array();
        $tenor_sum = array();
        $bass_sum = array();

        $sop_newbie_sum = array();
        $alto_newbie_sum = array();
        $tenor_newbie_sum = array();
        $bass_newbie_sum = array();

        $sop_others_sum = array();
        $alto_others_sum = array();
        $tenor_others_sum = array();
        $bass_others_sum = array();
        
        //------------------------------ get attendence log sum
        // sop
        $sop_sum = getAttStatForMonthlyReport('attendence_sopa', $sop_sum, $stat_date_start, $stat_date_end);
        $sop_sum = getAttStatForMonthlyReport('attendence_sopb', $sop_sum, $stat_date_start, $stat_date_end);
        $sop_sum = getAttStatForMonthlyReport('attendence_sopbp', $sop_sum, $stat_date_start, $stat_date_end);
        // echo json_encode($sop_sum).'<br>';
        //alto
        $alto_sum = getAttStatForMonthlyReport('attendence_altoa', $alto_sum, $stat_date_start, $stat_date_end);
        $alto_sum = getAttStatForMonthlyReport('attendence_altob', $alto_sum, $stat_date_start, $stat_date_end);
        // echo json_encode($alto_sum).'<br>';
        //tenor
        $tenor_sum = getAttStatForMonthlyReport('attendence_tenor', $tenor_sum, $stat_date_start, $stat_date_end);
        // echo json_encode($tenor_sum).'<br>';
        //bass
        $bass_sum = getAttStatForMonthlyReport('attendence_bass', $bass_sum, $stat_date_start, $stat_date_end);
        // echo json_encode($bass_sum).'<br>';
        
        //------------------------------ get total number of memeber
        $total_member_count = array(0,0,0,0);
        $total_newbie_count = 0;

        for($part_num=1; $part_num<8; $part_num++) {

            $att_list_ary = getMemberSNStateFromDB($part_num, $stat_date_end);
            $att_list_staff = $att_list_ary[0];
            $att_list_normal = $att_list_ary[1];
            $att_list_newbie = $att_list_ary[2];
            $att_list_temp = $att_list_ary[3];
            $att_list_special = $att_list_ary[4];
            $att_list_pause = $att_list_ary[5];

            if($part_num <= 3) {
                //sop
                $total_member_count[0] = $total_member_count[0] + count($att_list_staff);
                $total_member_count[0] = $total_member_count[0] + count($att_list_normal);
                $total_newbie_count = $total_newbie_count + count($att_list_newbie);
                $sop_newbie_sum = getNewbieAttStatForMonthlyReport($att_list_newbie, $sop_newbie_sum, $stat_date_start, $stat_date_end);
                $sop_others_sum = getNewbieAttStatForMonthlyReport($att_list_temp, $sop_others_sum, $stat_date_start, $stat_date_end);
                $sop_others_sum = getNewbieAttStatForMonthlyReport($att_list_special, $sop_others_sum, $stat_date_start, $stat_date_end);
                $sop_others_sum = getNewbieAttStatForMonthlyReport($att_list_pause, $sop_others_sum, $stat_date_start, $stat_date_end);
            } else if ($part_num >= 4 && $part_num <= 5) {
                //alto
                $total_member_count[1] = $total_member_count[1] + count($att_list_staff);
                $total_member_count[1] = $total_member_count[1] + count($att_list_normal);
                $total_newbie_count = $total_newbie_count + count($att_list_newbie);
                $alto_newbie_sum = getNewbieAttStatForMonthlyReport($att_list_newbie, $alto_newbie_sum, $stat_date_start, $stat_date_end);
                $alto_others_sum = getNewbieAttStatForMonthlyReport($att_list_temp, $alto_others_sum, $stat_date_start, $stat_date_end);
                $alto_others_sum = getNewbieAttStatForMonthlyReport($att_list_special, $alto_others_sum, $stat_date_start, $stat_date_end);
                $alto_others_sum = getNewbieAttStatForMonthlyReport($att_list_pause, $alto_others_sum, $stat_date_start, $stat_date_end);
            } else if ($part_num == 6) {
                //tenor
                $total_member_count[2] = $total_member_count[2] + count($att_list_staff);
                $total_member_count[2] = $total_member_count[2] + count($att_list_normal);
                $total_newbie_count = $total_newbie_count + count($att_list_newbie);
                $tenor_newbie_sum = getNewbieAttStatForMonthlyReport($att_list_newbie, $tenor_newbie_sum, $stat_date_start, $stat_date_end);
                $tenor_others_sum = getNewbieAttStatForMonthlyReport($att_list_temp, $tenor_others_sum, $stat_date_start, $stat_date_end);
                $tenor_others_sum = getNewbieAttStatForMonthlyReport($att_list_special, $tenor_others_sum, $stat_date_start, $stat_date_end);
                $tenor_others_sum = getNewbieAttStatForMonthlyReport($att_list_pause, $tenor_others_sum, $stat_date_start, $stat_date_end);
            } else if ($part_num == 7) {
                //bass
                $total_member_count[3] = $total_member_count[3] + count($att_list_staff);
                $total_member_count[3] = $total_member_count[3] + count($att_list_normal);
                $total_newbie_count = $total_newbie_count + count($att_list_newbie);
                $bass_newbie_sum = getNewbieAttStatForMonthlyReport($att_list_newbie, $bass_newbie_sum, $stat_date_start, $stat_date_end);
                $bass_others_sum = getNewbieAttStatForMonthlyReport($att_list_temp, $bass_others_sum, $stat_date_start, $stat_date_end);
                $bass_others_sum = getNewbieAttStatForMonthlyReport($att_list_special, $bass_others_sum, $stat_date_start, $stat_date_end);
                $bass_others_sum = getNewbieAttStatForMonthlyReport($att_list_pause, $bass_others_sum, $stat_date_start, $stat_date_end);
            } else {
                //
            }

        }
        // echo json_encode($total_member_count).'<br>';
        // echo $total_newbie_count.'<br>';
        // echo json_encode($sop_newbie_sum).'<br>';
        // echo json_encode($alto_newbie_sum).'<br>';
        // echo json_encode($tenor_newbie_sum).'<br>';
        // echo json_encode($bass_newbie_sum).'<br>';
        // echo json_encode($sop_others_sum).'<br>';
        // echo json_encode($alto_others_sum).'<br>';
        // echo json_encode($tenor_others_sum).'<br>';
        // echo json_encode($bass_others_sum).'<br>';

        $last_date_of_month = (int)date('t', strtotime($att_stat_date));
        $week = 1;
        //check 1st date is a sunday
        $flag_of_sunday = 0;
        $day_first_date_of_month = date('w', strtotime($stat_date_start));
        if($day_first_date_of_month == 0) {
            $flag_of_sunday = 1;
        }

        $str_html = '<table class="w3-table-all w3-hoverable" id="monthly_report_stat" style="width:700px"><tr><th></th><th></th><th>소프라노</th><th>알토</th><th>테너</th><th>베이스</th><th>신입</th><th>재적</th><th>계</th><th>%</th></tr>';
        $total_all_count = array_sum($total_member_count)+$total_newbie_count;
        $str_html = $str_html.'<tr><td></td><td>재적</td><td>'.$total_member_count[0].'</td><td>'.$total_member_count[1].'</td><td>'.$total_member_count[2].'</td><td>'.$total_member_count[3].'</td><td>'.$total_newbie_count.'</td><td>'.array_sum($total_member_count).'</td><td>'.($total_all_count).'</td><td></td></tr>';

        if($flag_of_sunday == 1) {
            $str_html = $str_html.'<tr><td>'.$week.'주차</td><td>연습</td><td>-</td><td>-</td><td>-</td><td>-</td><td>-</td><td>-</td><td>-</td><td>-</td></tr>';
        }

        $sop_sum_sat = 0;
        $sop_sum_sun = 0;
        $alto_sum_sat = 0;
        $alto_sum_sun = 0;
        $ten_sum_sat = 0;
        $ten_sum_sun = 0;
        $bass_sum_sat = 0;
        $bass_sum_sun = 0;
        $newbie_sum_sat = 0;
        $newbie_sum_sun = 0;
        $total_member_sat = 0;
        $total_member_sun = 0;
        $total_all_sat = 0;
        $total_all_sun = 0;
        $sat_cnt = 0;
        $sun_cnt = 0;
        $sop_att_cnt = 0;
        $alto_att_cnt = 0;
        $ten_att_cnt = 0;
        $bass_att_cnt = 0;
        $newbie_att_cnt = 0;

        for($date=1; $date<$last_date_of_month; $date++) {

            if($date<10) {
                $str_date = '0'.$date;
            } else {
                $str_date = $date;
            }
            
            $date_temp = date('Y-m', strtotime($att_stat_date)).'-'.$str_date;
            $day = date('w', strtotime($date_temp));

            if(($day == 6) || ($day == 0)) {

                $sop_att_cnt = 0;
                $alto_att_cnt = 0;
                $ten_att_cnt = 0;
                $bass_att_cnt = 0;
                $newbie_att_cnt = 0;

                //sop
                $sop_att_cnt = getAttCnt($sop_sum, $date) - getAttCnt($sop_newbie_sum, $date) - getAttCnt($sop_others_sum, $date);
                if($sop_att_cnt <= 0) {
                    $sop_att_cnt = 0;
                } else {
                    if($day == 6) {
                        $sop_sum_sat = $sop_sum_sat + $sop_att_cnt;
                        $sat_cnt = $sat_cnt + 1;
                    } else if ($day == 0) {
                        $sop_sum_sun = $sop_sum_sun + $sop_att_cnt;
                        $sun_cnt = $sun_cnt + 1;
                    }                    
                }

                //alto
                $alto_att_cnt = getAttCnt($alto_sum, $date) - getAttCnt($alto_newbie_sum, $date) - getAttCnt($alto_others_sum, $date);
                if($alto_att_cnt <= 0) {
                    $alto_att_cnt = 0;
                } else {
                    if($day == 6) {
                        $alto_sum_sat = $alto_sum_sat + $alto_att_cnt;
                    } else if ($day == 0) {
                        $alto_sum_sun = $alto_sum_sun + $alto_att_cnt;
                    }                    
                }

                //tenor
                $ten_att_cnt = getAttCnt($tenor_sum, $date) - getAttCnt($tenor_newbie_sum, $date) - getAttCnt($tenor_others_sum, $date);
                if($ten_att_cnt <= 0) {
                    $ten_att_cnt = 0;
                } else {
                    if($day == 6) {
                        $ten_sum_sat = $ten_sum_sat + $ten_att_cnt;
                    } else if ($day == 0) {
                        $ten_sum_sun = $ten_sum_sun + $ten_att_cnt;
                    }                    
                }

                //bass
                $bass_att_cnt = getAttCnt($bass_sum, $date) - getAttCnt($bass_newbie_sum, $date) - getAttCnt($bass_others_sum, $date);
                if($bass_att_cnt <= 0) {
                    $bass_att_cnt = 0;
                } else {
                    if($day == 6) {
                        $bass_sum_sat = $bass_sum_sat + $bass_att_cnt;
                    } else if ($day == 0) {
                        $bass_sum_sun = $bass_sum_sun + $bass_att_cnt;
                    }                    
                }

                if($day == 6) {
                    $str_date_name = '연습';
                } else if ($day == 0) {
                    $str_date_name = '주일';
                }

                //newbie
                $newbie_att_cnt = getAttNewbieCnt($sop_newbie_sum, $alto_newbie_sum, $tenor_newbie_sum, $bass_newbie_sum, $date);
                if($newbie_att_cnt <= 0) {
                    $newbie_att_cnt = 0;
                } else {
                    if($day == 6) {
                        $newbie_sum_sat = $newbie_sum_sat + $newbie_att_cnt;
                    } else if ($day == 0) {
                        $newbie_sum_sun = $newbie_sum_sun + $newbie_att_cnt;
                    }                    
                }

                //daily stat
                $date_att_normal = $sop_att_cnt+$alto_att_cnt+$ten_att_cnt+$bass_att_cnt;
                $date_att_all = $sop_att_cnt+$alto_att_cnt+$ten_att_cnt+$bass_att_cnt+$newbie_att_cnt;
                $date_att_rate = round(($date_att_all/$total_all_count)*100);

                if($day == 6) {
                    $total_member_sat = $total_member_sat + $date_att_normal;
                    $total_all_sat = $total_all_sat + $date_att_all;
                } else if ($day == 0) {
                    $total_member_sun = $total_member_sun + $date_att_normal;
                    $total_all_sun = $total_all_sun + $date_att_all;
                }


                if($sop_att_cnt == 0) {
                    $sop_att_cnt = '-';
                }
                if($alto_att_cnt == 0) {
                    $alto_att_cnt = '-';
                }
                if($ten_att_cnt == 0) {
                    $ten_att_cnt = '-';
                }
                if($bass_att_cnt == 0) {
                    $bass_att_cnt = '-';
                }
                if($newbie_att_cnt == 0) {
                    $newbie_att_cnt = '-';
                }
                
                $str_html = $str_html.'<tr><td>'.$week.'주차</td><td>'.$str_date_name.'('.$date.')</td><td>'.$sop_att_cnt.'</td><td>'.$alto_att_cnt.'</td><td>'.$ten_att_cnt.'</td><td>'.$bass_att_cnt.'</td><td>'.$newbie_att_cnt.'</td><td>'.$date_att_normal.'</td><td>'.$date_att_all.'</td><td>'.$date_att_rate.'%</td></tr>';

                if($day == 0) {
                    $week = $week + 1;
                }
                $date_att_normal = 0;
                $date_att_all = 0;
            }

        }
        $total_sat_att_rate = round((round($total_all_sat/$sat_cnt)/$total_all_count)*100);
        $total_sun_att_rate = round((round($total_all_sun/$sun_cnt)/$total_all_count)*100);

        $str_html = $str_html.'<tr><td>월평균</td><td>연습</td><td>'.round($sop_sum_sat/$sat_cnt).'</td><td>'.round($alto_sum_sat/$sat_cnt).'</td><td>'.round($ten_sum_sat/$sat_cnt).'</td><td>'.round($bass_sum_sat/$sat_cnt).'</td><td>'.round($newbie_sum_sat/$sat_cnt).'</td><td>'.round($total_member_sat/$sat_cnt).'</td><td>'.round($total_all_sat/$sat_cnt).'</td><td>'.$total_sat_att_rate.'%</td></tr>';
        $str_html = $str_html.'<tr><td>월평균</td><td>주일</td><td>'.round($sop_sum_sun/$sun_cnt).'</td><td>'.round($alto_sum_sun/$sun_cnt).'</td><td>'.round($ten_sum_sun/$sun_cnt).'</td><td>'.round($bass_sum_sun/$sun_cnt).'</td><td>'.round($newbie_sum_sun/$sun_cnt).'</td><td>'.round($total_member_sun/$sun_cnt).'</td><td>'.round($total_all_sun/$sun_cnt).'</td><td>'.$total_sun_att_rate.'%</td></tr>';

        $str_html = $str_html.'</table>';
        
    }

    function getAttCnt($array, $key) {
        $ret = 0;

        if(array_key_exists($key, $array)) {
            $ret = $array[$key];
        } else {
        }

        return $ret;
    }

    function getAttNewbieCnt($sop_ary, $alto_ary, $tenor_ary, $bass_ary, $key) {
        $ret_sum = 0;

        if(array_key_exists($key, $sop_ary)) {
            $sop_cnt = $sop_ary[$key];
        } else {
            $sop_cnt = 0;
        }
        $ret_sum = $ret_sum + $sop_cnt;

        if(array_key_exists($key, $alto_ary)) {
            $alto_cnt = $alto_ary[$key];
        } else {
            $alto_cnt = 0;
        }
        $ret_sum = $ret_sum + $alto_cnt;

        if(array_key_exists($key, $tenor_ary)) {
            $tenor_cnt = $tenor_ary[$key];
        } else {
            $tenor_cnt = 0;
        }
        $ret_sum = $ret_sum + $tenor_cnt;

        if(array_key_exists($key, $bass_ary)) {
            $bass_cnt = $bass_ary[$key];
        } else {
            $bass_cnt = 0;
        }
        $ret_sum = $ret_sum + $bass_cnt;

        return $ret_sum;
    }

    function getAttStatForMonthlyReport($db_name, $sum_array, $start_date, $end_date) {

        include "dbconn.php";
        
        $query = "SELECT date, sum(attend_value) FROM ".$db_name." WHERE date IN (SELECT att_date FROM attendence_date WHERE type=:in1 AND att_date>='".$start_date."' AND att_date<='".$end_date."' ORDER BY att_date ASC) GROUP BY date;";

        $stmt = $conn->prepare($query);
        $stmt->bindParam(':in1', $in1);

        $in1 = 0;
        $stmt->execute();
        $num_of_rows = $stmt->rowCount();

        if($num_of_rows > 0) {
            while($row = $stmt->fetch()) {
                $date = (int)date('j', strtotime($row['date']));
                // echo $date.'<br>';
                if(array_key_exists($date, $sum_array)) {
                    $sum_array[$date] = $sum_array[$date]+(int)($row['sum(attend_value)']/10);
                } else {
                    $sum_array[$date] = (int)($row['sum(attend_value)']/10);
                }
            }
        }

        $in1 = 6;
        $stmt->execute();
        $num_of_rows = $stmt->rowCount();

        if($num_of_rows > 0) {
            while($row = $stmt->fetch()) {
                $date = (int)date('j', strtotime($row['date']));
                // echo $date.'<br>';
                if(array_key_exists($date, $sum_array)) {
                    $sum_array[$date] = $sum_array[$date]+(int)($row['sum(attend_value)']/10);
                } else {
                    $sum_array[$date] = (int)($row['sum(attend_value)']/10);
                }
            }
        }

        return $sum_array;
    }

    function getNewbieAttStatForMonthlyReport($mem_list, $sum_array, $start_date, $end_date) {

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
                    if(array_key_exists($date, $sum_array)) {
                        $sum_array[$date] = $sum_array[$date]+(int)($row['sum(attend_value)']/10);
                    } else {
                        $sum_array[$date] = (int)($row['sum(attend_value)']/10);
                    }
                }
            }

            $in2 = 6;
            $stmt->execute();
            $num_of_rows = $stmt->rowCount();

            if($num_of_rows > 0) {
                while($row = $stmt->fetch()) {
                    $date = (int)date('j', strtotime($row['date']));
                    // echo $date.'<br>';
                    if(array_key_exists($date, $sum_array)) {
                        $sum_array[$date] = $sum_array[$date]+(int)($row['sum(attend_value)']/10);
                    } else {
                        $sum_array[$date] = (int)($row['sum(attend_value)']/10);
                    }
                }
            }

        }

        return $sum_array;
    }

?>