<?php

    if(isset($_POST['search_submit'])) {
        $record_id = $_POST['att_date_select'];
        $part_num = $_POST['att_part'];

        header("Location: ./attendence_log_part.php?part_num=".$part_num."&id=".$record_id);
    }

?> 