<?php
function smarty_modifier_lang($string) {
    $Lang = new Apollo_Lang();
    return $Lang::lang($string);
}
