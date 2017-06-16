<?php
function smarty_modifier_json_str($array) {
    $str = json_encode((object)$array);
    return $str;
    //return str_replace("\\", '&#39', $str);
}
