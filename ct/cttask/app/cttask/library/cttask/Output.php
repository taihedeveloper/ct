<?php
/**
 * @name Cttask_Output
 * @desc APP 输出封装
 * @author 冯新(fengxin@taihe.com)
 */
class Cttask_Output
{
    /**
     * @brief 输出json字符串
     *
     * @param array $arr
     * @return output
     */
    public static function json($arr = [])
    {
        header('Content-type: application/json');
        $callback = isset($_GET['callback']) ? $_GET['callback'] : '';
        if (!$callback)
        {
            echo json_encode($arr);
            return;
        }
        //安全过滤
        $hasUnSafeChar = preg_match('/[^a-zA-Z0-9_]+/', $callback);
        if ($hasUnSafeChar)
        {
            $callback = 'callback';
        }
        //增加js注释 防止低版本flash漏洞(可将返回值开头为CWS的字符串,当作代码执行,从而进行跨域操作,获取隐私数据)
        echo '/**/' . $callback . "(" . json_encode($arr,JSON_UNESCAPED_UNICODE) . ")";
    }
}
