<?php
/**
 * @name index
 * @desc 入口文件
 * @author 刘重量(v_liuzhongliang@baidu.com)
 */
$objApplication = Bd_Init::init();
$objResponse = $objApplication->bootstrap()->run();
