<?php
function smarty_modifier_sys_lang($string) {
    $Lang = new Apollo_Lang();
    echo $string;
}
