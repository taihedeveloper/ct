<?php
function smarty_modifier_pagination($pagination = array()) {
    if (!$pagination) {
        return '';
    }
    if (!$pagination['total']) {
        return '';
    }
    $page = $pagination['page'];
    $size = $pagination['size'];
    $total = $pagination['total'];
    $max_page = ceil($total / $size);
    $prev_page = $page - 1;
    $next_page = $page + 1;
    $base_link =  '';
    $prev_link = '';
    $next_link = '';
    $get_param = $_GET;
    $display_page_nums = array();//展现的页码

    if($_SERVER['PATH_INFO'] == '/cttask/index.php'){
        $_SERVER['PATH_INFO'] = '/';
    }
    $uri = '/' . Bd_AppEnv::getCurrApp() . $_SERVER['PATH_INFO'];
    $uri .= '?';

    if ($prev_page > 0) {
        $param = $get_param;
        $param['page'] = $prev_page;
        $param['size'] = $size;
        $prev_link = $uri . http_build_query($param);
    }
    if ($next_page <= $max_page) {
        $param = $get_param;
        $param['page'] = $next_page;
        $param['size'] = $size;
        $next_link = $uri . http_build_query($param);
    }
    if ($max_page < 10) {
        $display_page_nums = range(1, $max_page);
    } else {
        //超出10页 做处理 一直展示首页和尾页,其余根据当前页来判断
        $first = 1;
        $start = $page - 3;
        $end = $page + 3;
        if ($start <= $first) {
            $end += abs($start);
            if ($end < 8) {
                $end = 8;
            }
            $start = $first + 1;
        }
        if ($end >= $max_page) {
            $start -= abs($max_page - $end);
            $end = $max_page - 1;
        }

        $middle = range($start, $end);
        $display_page_nums[] = $first;
        if (($start - $first) > 1)
        {
            $display_page_nums[] = '...';
        }
        $display_page_nums = array_merge($display_page_nums, $middle);
        $last = array_pop($middle);
        if (($max_page - $end) > 1)
        {
            $display_page_nums[] = '...';
        }

        $display_page_nums[] = $max_page;
    }

    $html = '';
    $html .= '<ul class="pagination">';
    if (!$prev_link) {
        $html .= '<li class="disabled"><a href="#">&laquo;</a></li>';
    } else {
        $html .= '<li><a href="' . $prev_link . '">&laquo;</a></li>';
    }
    foreach ($display_page_nums as $page_num) {
        if ($page_num == '...') {
            $html .= '<li><a href="javascript:void(0);" class="disable">' . $page_num . '</a></li>';
            continue;
        }
        $param = $get_param;
        $param['page'] = $page_num;
        $param['size'] = $size;
        $link = $uri . http_build_query($param);
        if ($page == $page_num)
        {
            $html .= '<li class="active"><a href="' . $link . '">' . $page_num . '</a></li>';
        }
        else
        {
            $html .= '<li><a href="' . $link . '">' . $page_num . '</a></li>';
        }
    }
    if (!$next_link) {
        $html .= '<li class="disabled"><a href="#">&raquo;</a></li>';
    } else {
        $html .= '<li><a href="' . $next_link . '">&raquo;</a></li>';
    }
    $html .= '</ul>';
    return $html;
}
