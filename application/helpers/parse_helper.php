<?php
function Parse_Data($data, $start_tag, $end_tag) {
    $data = " " . $data; // Add a leading space to ensure strpos works correctly
    $result = "";

    $start_pos = strpos($data, $start_tag);
    if ($start_pos !== false) {
        $start_pos += strlen($start_tag);
        $end_pos = strpos($data, $end_tag, $start_pos);
        if ($end_pos !== false) {
            $result = substr($data, $start_pos, $end_pos - $start_pos);
        }
    }
    return $result;
}
?>