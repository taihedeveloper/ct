/**
 * @file 数据月份js
 * @author 刘重量(v_liuzhongliang@domain.com)
 */
/**
 * 加载完后执行
 * @param {string=} this 文档自身
 */
$(document).ready(function (e) {
    var d = $.trim($('#data_month').val());
    if (d !== '') {
        var y = d.slice(0, 4);
        var m = d.slice(-2);
        $('#year').val(y);
        $('#month').val(m);
    }
    else {
        var s = new Date();
        $('#year').val(s.getFullYear());
        var m = s.getMonth();
        ++m;
        if (m < 10) {
            m = '0' + m;
        }
        $('#month').val(m);
    }
    function datamonth() {
        var year = $('#year').val();
        var month = $('#month').val();
        $('#data_month').val(year + month);
    }
    $('#year').change(function () {
        datamonth();
    });
    $('#month').change(function () {
        datamonth();
    });
    datamonth();
});

